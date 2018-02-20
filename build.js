const sass = require('node-sass');
const fs = require('mz/fs');
const babel = require('babel-core');
const glob = require('glob');
const path = require('path');
const AsyncArray = require('./async_array.js')
const package = require('./package.json');
const util = require('util');
const exec = util.promisify(require('child_process').exec);

const rollup = require('rollup');
const babelPlugin = require('rollup-plugin-babel');
const resolve = require('rollup-plugin-node-resolve');
const minify = require('rollup-plugin-babel-minify');


var browserSync = require('browser-sync').create();
var mysql      = require('mysql');

const wpPot = require('wp-pot');
// Configs ---------------------------------------------------------------------------------------------
const cssoConfig = {
  restructure: false,
  sourceMap: true,
};


const babelConfig = {
  presets: ["babili"],
  sourceMaps: true
};

const funcTable = {
  "genpot": [genpot],
  "watch": [watch],
  "wpDev": [wpDev],
  "png,jpeg,jpg,svg,woff2,eot,ttf,otf,php,txt,zip,html,xml,pot,js": [copyStatic],
  "js": [minifyJs],
  "css": [minifyCss],
  "sass,scss": [handleSass],
  "default": [noop],
  "siteMap": [createSiteMap]
}

const templateData = {
  'VERSION': package.version,
};


if (process.argv.length > 2) { 
  // With params
  call(process.argv.slice(2));

}else{
  // Standard build
  Promise.all([
    copyStatic(),
    copySystemJS(),
    copyCustomElements(),
    handleSass(),
    minifyCss(),
    minifyJs(),
  ])
    .then(_ => console.log('Done All'))
    .catch(err => console.log(err.stack, '\nError while building'));
}

// Build Func ------------------------------------------------------------------------------------------

async function call(task, ... opt) {
  console.time('task');
  
  fnStack = funcTable['default'];
  Object.entries(funcTable).map(item => {
    if(item[0].split(/(\,)/).some(extComp => task.includes(extComp))){
      fnStack = item[1];
    }
  })

  await fnStack.reduce(async (p, fn) => {
    await p;
    return fn(...opt); 
  }, Promise.resolve())
    .then(_ => {})
    .catch(err => console.log(err.stack, '\nError while building'));

  console.timeEnd('task'); 
  console.log('Done');


  return Promise.resolve()
}

function watch(){

  fs.watch('./src', { recursive: true }, debounce(async (eventType, filename) => {
    let task = filename.split(/\.(.{2,4}$)/);

    switch (eventType) {
      case "rename":
        files = filesDist = null;
        if(fs.existsSync(`./src/${filename.split('\\').join('/')}`)) { // win bug
        
          console.log('\x1b[36m%s\x1b[0m', 'create');
          await call(task[task.length - 2], filename)
  
        }else{
  
          console.log('\x1b[36m%s\x1b[0m', 'delete');
          try{
            await fs.unlink(`./dist/${filename.split('\\').join('/')}`);  // win bug
          }
          catch(err) { }
        }
        
        call('wpDev');

        break;
      case "change":
        
        call(task[task.length - 2 ], filename)
        break;
    
      default:
        break;
    }

  },500));

  return Promise.resolve();

}


// Tast Funcs -----------------------------------------------------------------------------------------

async function copyStatic() {
  console.time('copyStatic');
  await filesWithPatterns([/\.php$/i, /\.htaccess$/i, /\.(png|jpe?g|svg)$/i, /\.(woff?2|eot|ttf|otf)$/i, /\.xml$/i, /\.txt$/i, /\.zip$/i, /\.html$/i, /\.md$/i, /\.pot$/i, , /\.min\.js$/i])
    .map(async file => copy(`src/${file}`, `dist/${file}`))
    .array || Promise.resolve(); 
  console.timeEnd('copyStatic')
}

async function copySystemJS() {
  console.time('copySystemJS');
  const file = await fs.readFile('./node_modules/systemjs/dist/system-production.js');
  const contents = file.toString('utf-8');
  const { code } = babel.transform(contents, babelConfig);
  const SystemJsDir = 'dist/theme/assets/js/system.js';
  await mkdirAll(path.dirname(SystemJsDir));
  await fs.writeFile(`${path.dirname(SystemJsDir)}/${path.basename(SystemJsDir)}`, code);
  console.timeEnd('copySystemJS')
}

async function copyCustomElements() {
  console.time('copyCustomElements');
  const file = await fs.readFile('./node_modules/@webcomponents/custom-elements/custom-elements.min.js');
  const contents = file.toString('utf-8');
  const { code } = babel.transform(contents, babelConfig);
  await fs.writeFile('dist/theme/assets/js/custom-elements.js', code);
  console.timeEnd('copyCustomElements')
}


async function minifyCss() {
  console.time('minifyCss');
  filesWithPatterns([/\.css$/i])
    .map(async file => ({ name: file, contents: await fs.readFile(`src/${file}`) }))
    .map(async file => Object.assign(file, { contents: file.contents.toString('utf-8') }))
    .map(async file => {
      for (const [key, val] of Object.entries(templateData)) {
        file.contents = file.contents.replace(`{%${key}%}`, val);
      }
      return file
      //const cssoConfigCopy = Object.assign({}, cssoConfig, {filename: file.name});
      //return Object.assign(file, {csso: csso.minify(file.contents, cssoConfigCopy)});
    })
    .map(async file => {
      await mkdirAll(path.dirname(`dist/${file.name}`));
      await fs.writeFile(`dist/${file.name}`, `${file.contents}`);
      //await fs.writeFile(`dist/${file.name}.map`, file.csso.map.toString());
    })
    .array;
  console.timeEnd('minifyCss')
}

async function handleSass() {
  console.time('handleSass');
  filesWithPatterns([/^(?:(?!\/\_.*\.scss$).)*\.scss$/i, /^(?:(?!\/\_.*\.sass$).)*\.sass$/i])
    .map(async file => {
      return {
        name: file, contents: sass.renderSync({
          file: `${process.cwd()}/src/${file}`,
          includePaths: [`${process.cwd()}/src/${file}`],
          outputStyle: 'expanded'
        })
      }
    })
    .map(async file => {
      for (const [key, val] of Object.entries(templateData)) {
        file.contents = file.contents.css.toString().replace(`{%${key}%}`, val);
      }
      await fs.writeFile(`dist/theme/${path.basename(file.name).split('.')[0]}.css`, file.contents);
      return file
      
    }).array.catch(err => {
      console.log('\x1b[31m','CSS');
      console.log(err.formatted)
      console.log('\x1b[37m');
    })

  console.timeEnd('handleSass')
}




var cache;
async function minifyJs(filename) {
  console.time('minifyJs');
  console.log(filename)
  const orig = filesWithPatterns([/^(?:(?!\.min\.js$).)*\.js$/i])
    .map(async file  => {
      const name = file;
      //if(filename && filename.split('\\').join('/') != name) return

      console.log('bundling... \n %s', path.parse(name).name)
      const bundle = await rollup.rollup({
        input: `src/${name}`, 
        plugins: [
          resolve(),
          babelPlugin({
            exclude: 'node_modules/**' // only transpile our source code
          }),
          minify({
            comments: false
          })
        ],
        cache
      });

      cache = bundle;
      let outputOptions = {
        format: 'iife',
        globals: {
          jquery: '$'
        },
        file: `dist/${name}`,
        name: path.parse(name).name.split('-').join('_')
      };
      const { code, map } = await bundle.generate(outputOptions);
      await bundle.write(outputOptions);

      return  { code, map, name }
    }) 
    .array;
  
  console.timeEnd('minifyJs')
  return await Promise.all([orig]);
}


// Link files with folder in wordpress

async function wpDev() {
  console.log('\x1b[36m%s\x1b[40m', 'Linking ...');  
  let paths = await filesWithPatternsDist([/theme\/.*\..{2,4}$/])
    .map(async filePath => {
      return `ln -f d:/OpenServer/domains/wordpress/wp-content/startwp/dist/theme/${filePath.substr(7)} d:/OpenServer/domains/wordpress/wp-content/themes/bitstarter/${path.dirname(filePath.substr(7))} \n`
    })
    .array;
  let pre = `rm -rf d:/OpenServer/domains/wordpress/wp-content/themes/bitstarter/
             mkdir d:/OpenServer/domains/wordpress/wp-content/themes/bitstarter
              
            ln -fs d:/OpenServer/domains/wordpress/wp-content/startwp/dist/theme/* d:/OpenServer/domains/wordpress/wp-content/themes/bitstarter/ \n`; // hack to create dir

  await fs.writeFile('wp-dev.sh', paths.reduce((acc, val) => acc.concat(val), pre));
  const { stdout, stderr } = await exec('sh wp-dev.sh');
  if (stderr) console.log('stderr:', stderr);

} 




// Change onSave with Broserify ------------------------------------------------------------------------

function serve(){

  browserSync.init({
    files: [
      "src/theme/**/*.php",
      "src/theme/**/*.js",
      "src/theme/**/*.sass",
      "src/theme/**/*.scss",
      "src/theme/**/*.png",
      "src/theme/**/*.svg"
    ],
    exclude: false,
    proxy: "wordpress",
    port: 8080,
    server: false, // It should NOT be used if you have an existing PHP, WordPress.
    startPath: null,
    ghostMode: {
      clicks: true,
      links: true,
      forms: true,
      scroll: true
    },
    open: false,
    xip: false,
    timestamps: true,
    fileTimeout: 1000,
    injectChanges: true,
    scrollProportionally: true,
    scrollThrottle: 0,
    notify: true,
    host: null,
    excludedFileTypes: [],
    reloadDelay: 0
  });
  return Promise.resolve();
}


// Utils func ------------------------------------------------------------------------------------------

async function copy(from, to) {
  const data = await fs.readFile(from);
  const dir = path.dirname(to);
  await mkdirAll(dir);
  await fs.writeFile(to, data);
}

async function mkdirAll(dir) {
  const elems = dir.split('/');
  await elems.reduce(async (p, newPath) => {
    const oldPath = await p;
    const newDir = path.join(oldPath, newPath);
    await fs.mkdir(newDir).catch(_ => { });
    return newDir;
  }, Promise.resolve(''));
}

function flatten(arr) {
  return Array.prototype.concat.apply([], arr);
}

function debounce(func, wait) {
  var timeout;
  var eventTypeLast;
  return function () {
    var context = this, args = arguments, eventType = args[0];
    
    var later = function () {
      timeout = null;
      func.apply(context, args);
    };

    if (eventTypeLast && eventTypeLast === eventType)
      clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    eventTypeLast = args[0];
  };
};

function noop() {console.log('Not found.')}




function createSiteMap(){

  var connection = mysql.createConnection({
    host     : 'localhost',
    user     : 'root',
    password : '',
    database : 'bitstarter'
  });
  let pages = new Set(),
      acc = '';
  
  connection.connect();
  connection.query(`SELECT guid 
                    FROM  wp_posts 
                    WHERE guid REGEXP 'wordpress/.p' 
                    AND guid NOT LIKE '%wp-content%' 
                    LIMIT 300`,
  function (error, results, fields) {
    if (error) throw error;
    results.map( o => pages.add( o.guid ));
    for (let item of pages.values()) acc += `- file: ${item} \n  ready: 50\n`;
    fs.writeFile('site-map.yaml',`ProjectTitle: Bitstarter \nPageList:\n`.concat(acc));
  });
  
  connection.end();

}

// Create obsorved files -------------------------------------------------------------------------------
var files;
function filesWithPatterns(regexps) {
  if (!files) {
    files = AsyncArray.from(new Promise((resolve, reject) => glob('src/**', { dot: true }, (err, f) => err ? reject(err) : resolve(f))))
      .map(async file => file.substr(4));
  }
  return files.filter(async file => regexps.some(regexp => regexp.test(file)));
}


var filesDist;
function filesWithPatternsDist(regexps) {
  if (!filesDist) {
    filesDist = AsyncArray.from(new Promise((resolve, reject) => glob('dist/**', { dot: true }, (err, f) => err ? reject(err) : resolve(f))))
      .map(async file => file.substr(4));
  }
  return filesDist.filter(async file => regexps.some(regexp => regexp.test(file))); 
}


function genpot(){
  console.log('generate pot')
  wpPot({
    destFile: './bitstarter.pot',
    domain: 'bitstarter',
    package: 'Bitstarter',
    src: './dist/theme/**/*.php'
  });
}