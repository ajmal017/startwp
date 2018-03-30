

// let url = new URL('v1/ticker/','https://api.coinmarketcap.com');
// fetch(url.href, {
//     method: 'GET',
//     mode: 'cors'
// }).then(res => res.json()).then( data => {
//     let res = data.reduce((acc, cur) => {
//         return acc += `<option title="${cur.name}" value="${cur.id}">${cur.symbol}</option>\n`; 
//     }, '')
//     console.log(res)
    
// });


class ModelEntry {
    /**
     * Constructor for ModelEntry.
     * @param {string} name The unique name used for binding to this entry.
     * @param {*} value The initial value to assign
     * @param {[function(value)]} listeners The initial set of listeners to add.
     */
    constructor(name = null, value = null, listeners = []) {
      this._name = name;
      this._value = value;
      this._listeners = listeners;
      this._bound =
          this._name ? document.querySelectorAll(`[data-bind='${name}']`) : [];
    }
  
    /**
     * Getter for the value of the entry.
     * @return {*} The value.
     */
    get value() {
      return this._value;
    }
  
    /**
     * Setter for the value of the entry.
     * @param {*} val The value.
     */
    set value(val) {
      this._value = val;
  
      for (const listener of this._listeners) {
        listener(this._value);
      }
  
      for (let i = 0; i < this._bound.length; i++) {
        this._bound[i].textContent = this._value;
      }
    }
  
    /**
     * Attach a listener to this entry, to be notified of changes.
     * @param {function(val)} listener The listening function to attach.
     */
    listen(listener) {
      this._listeners.push(listener);
    }
    
    /**
     * Detach a listener from this entry.
     * @param {function(val)} listener The listening function to detach.
     */
    unlisten(listener) {
      const index = this._listeners.indexOf(listener);
      if (index > -1) {
        this._listeners.splice(index, 1);
      }
    }
  }

/**
 * Format a currency for display.
 * @param {number} value The value to format
 * @return {string} The formatted value
*/

 export function _formatCurrency(value) {
    const whole = Math.floor(Number(value));
    const decimal = Math.abs(Math.round((Number(value) % 1) * 100));
    const marker = (1.1).toLocaleString().charAt(1);
  
    if (decimal < 10) {
      return `${whole.toLocaleString()}${marker}0${decimal.toString()}`;
    }
    return `${whole.toLocaleString()}${marker}${decimal.toString()}`;
  }


  /**
   * The main class for app
  */


  class App{
      /**
       * Constructor takes no params
      */
    constructor() {
        
        this._model = {
            coin: {
                metaText: 'coin',
                amount: new ModelEntry('coin.amount'),
                computedAmount: new ModelEntry('coin.computedAmount'),
                name: new ModelEntry('coin.name'),
            },
            currency: {
                metaText: 'currency',
                amount: new ModelEntry('currency.amount'),
                computedAmount: new ModelEntry('currency.computedAmount'),
                name: new ModelEntry('currency.name'),
            },
            income: {
                day: new ModelEntry('day.income'),
                week: new ModelEntry('week.income'),
                month: new ModelEntry('month.income'),
                symbol: new ModelEntry('symbol.income'),
            },
            data: new ModelEntry(/* Not bindable */)
        };


        // Load currency list.
        this._fetchCurrencies()
                // Initialize model.
            .then(() => this._initModel())
            // Initialize application logic and UI elements.
            .then(() => {
                this._init();
                this._initElements();
            });
    }

    /**
     * Initialize model. Sets up listeners for ensuring internal consistency.
     */
    _initModel() {
        const {coin, currency, income} = this._model;



        const convertInModel = (from, to) => {
            const amount = from.amount.value;

            if(amount || amount === 0){
                from.computedAmount.value = null;
                let valueOrPromise = this._convertValue(amount, from.name.value, to.name.value);
                if( typeof valueOrPromise.then === "function" ) {
                    valueOrPromise.then(_formatCurrency).then(val => (to.computedAmount.value = val));
                }else{
                    to.computedAmount.value = _formatCurrency(valueOrPromise);
                }
            }

        };


        // Update computed values when value changes.
        coin.amount.listen(() => convertInModel(coin, currency));
        currency.amount.listen((val) => {
            convertInModel(currency, coin);
            convertIncome(val);
        });
        currency.computedAmount.listen((val) => {

            convertIncome(val && val.replace(',',''));
        });

        // Update computed values when currency cyrrency changes.
        coin.name.listen(() => {
            convertComputed();
        });
        currency.name.listen((val) => {
            switch (val) {
                case 'USD':
                    this._model.income.symbol.value = '$';
                    break;
                case 'EUR':
                    this._model.income.symbol.value = '€';
                    break;
                default:
                    this._model.income.symbol.value = val;
                    break;
            }

            convertComputed();
        });

        let incomeMap = new Map([['day',1], ['week',7], ['month',30]]);

        const convertIncome = (val = 0) => {
            for (let [name,time] of incomeMap) {

              if (this._rise && val) {
                income[name].value =  Math.round(time * (this._rise / 100) * parseFloat(val) * 100) / 100;
              }
            }
        };
    
        const convertComputed = () => {
            if (coin.computedAmount.value !== null) {
                convertInModel(currency, coin);
            } else {
                convertInModel(coin, currency);
            }
        };

        
    }


    
  /**
   * Initialize application.
   */
  _init() {
    // Trigger recalc.
    this._rise = parseFloat(document.querySelector('#bitstarter-rise').getAttribute('data-rise'));
    this._model.coin.name.value = 'bitcoin';
    this._model.currency.name.value = 'USD';
    this._model.currency.amount.value = this._model.data.value.get('bitcoin-USD');
    this._model.income.symbol.value = '$';

}

   /**
   * Initialize the main screen elements.
   */
  _initElements() {

    const cryptoName = document.querySelector('#bitstarter-calc-cryptoname');
    const currenctName = document.querySelector('#bitstarter-calc-currencyname');


    // Set up animations for conversion screen buttons.
    cryptoName.addEventListener('change', (e) => {
        this._model.coin.name.value = event.target.value;
    });

    currenctName.addEventListener('change', (e) => {
        this._model.currency.name.value = event.target.value;
    });

    this._cryptoAmount = document.querySelector('#bitstarter-calc-cryptoamount');
    this._currencyAmount = document.querySelector('#bitstarter-calc-currencyamount');
    this._cryptoBlock = document.querySelector('.bitstarter-calc__entry__coin');
    this._currencyBlock = document.querySelector('.bitstarter-calc__entry__currency');
    // Set up event listeners for modifying the model.
    this._cryptoAmount.addEventListener('input', () => {
      this._model.coin.amount.value = parseFloat(this._cryptoAmount.value);
      this._validateInput('coin');
    });

    this._currencyAmount.addEventListener('input', () => {
      this._model.currency.amount.value = parseFloat(this._currencyAmount.value);
      this._validateInput('currency');
    });

    
    const marker = (1.1).toLocaleString().charAt(1);
    this._cryptoAmount.placeholder = `1${marker}00`;

    this._currencyAmount.value = this._model.data.value.get('bitcoin-USD');

    // Set up model listeners for input boxes.
    // Note: Programmatic value changes don't trigger DOM events, so we avoid
    // infinite loops.
    this._model.currency.computedAmount.listen((value) => {
      // A real computed value means we should clear the input.
      if (value !== null) {
        this._currencyAmount.placeholder = value;
        this._currencyAmount.value = '';
      }
      // If we have a computed value, it's probably because the other box has
      // a value, so let's check if we can clear the placeholder.
      if (this._cryptoAmount.value !== '') {
        this._cryptoAmount.placeholder = '';
      }
    });
    this._model.coin.computedAmount.listen((value) => {
      // A real computed value means we should clear the input.
      if (value !== null) {
        this._cryptoAmount.value = '';
        this._cryptoAmount.placeholder = value;
      }
      // If we have a computed value, it's probably because the other box has
      // a value, so let's check if we can clear the placeholder.
      if (this._currencyAmount.value !== '') {
        this._currencyAmount.placeholder = '';
      }
    });
  }


    /**
     * Returns a promise for the currency data.
     *
     * @return {Promise.<Object[]>} The constructed promise.
     */
    _fetchCurrencies() {

        let url = new URL('v1/ticker/','https://api.coinmarketcap.com');
        return fetch(url.href, {
            method: 'GET',
            mode: 'cors'
        }).then(res => res.json()).then((result) => 
            (this._model.data.value = new Map(result.reduce((acc, val) => {
                return acc.concat([[`${val.id}-USD` , val.price_usd], [`USD-${val.id}` , 1/val.price_usd]])},[])))).catch((err) => {
                    this._model.data.value = new Map();
                    console.error(err.stack)
                })
    
    }

    /**
     * Depend on slug fetchs, adds to data and returns a promise for converting multiplier.
     *
     * @param {string} slug
     * @return {Promise.<number>} multiplier.
     */
    _fetchCurrency(slug) {

        let url = new URL(`v1/ticker/${this._model.coin.name.value}/`,'https://api.coinmarketcap.com');
        let params = new URLSearchParams("convert=" + this._model.currency.name.value);
        url.search = params;

        return fetch(url.href, {
            method: 'GET',
            mode: 'cors'
        }).catch((err) => {
            console.error(err.stack)
            return 0
        }).then(res => res.json()).then(([result]) => {
            let key1 = `${this._model.coin.name.value}-${this._model.currency.name.value}`,
                key2 = `${this._model.currency.name.value}-${this._model.coin.name.value}`,
                currency = this._model.currency.name.value.toLowerCase();

            this._model.data.value.set(key1, result[`price_${ currency }`])
            this._model.data.value.set(key2, 1/result[`price_${ currency }`])
            
            if(key1 === slug){
                return  result[`price_${ currency}`]
            }

            if(key2 === slug){
                return 1/result[`price_${ currency }`]
            }

            return 0

        })

   
    }

    /**
     * Converts an amount between currencies.
     * @param {number} value The amount to convert.
     * @param {string} fromCur The 3-letter code of the currency to convert from.
     * @param {string} toCur The 3-letter code of the currency to convert to.
     * @return {number} The converted amount.
     */
    _convertValue(value, fromCur, toCur) {
        let conversion = null;
        value = value || 0;
        if(this._model.data.value.has(`${fromCur}-${toCur}`)){
            conversion = value * this._model.data.value.get(`${fromCur}-${toCur}`);
            return Math.round(conversion * 100) / 100;
        }else{
            return this._fetchCurrency(`${fromCur}-${toCur}`).then(conversion =>
                ( Math.round(value * conversion * 100) / 100))
        }
    }

    
    /**
     * Validate the provided input field.
     * @param {string} type One of 'home' or 'travel'.
     */
    _validateInput(type) {
        let input = null;
        let block = null;
        let otherBlock = null;

        if (type === 'coin') {
            block = this._cryptoBlock;
            input = this._cryptoAmount;
            otherBlock = this._currencyBlock;
        } else {
            block = this._currencyBlock;
            input = this._currencyAmount;
            otherBlock = this._cryptoBlock;
        }

        const isEmpty = input.value === '' && input.validity &&
            input.validity.valid;

        if (!isEmpty && isNaN(parseFloat(input.value))) {
            block.classList.add('invalid');
            otherBlock.classList.remove('invalid');

        } else {
            block.classList.remove('invalid');
            otherBlock.classList.remove('invalid');
        }
    }

  }





/**
 * Returns a promise preparing our key-value store on IndexedDB.
 *
 * @return {Promise.<IDBDatabase>} Promise to the IndexedDB database.
 */
 function prepareDb_() {
    return new Promise(function(resolve, reject) {
      if (self.indexedDB) {
        let req = self.indexedDB.open('db', 1);
        if (req) {
          req.onerror = (event) => reject(event);
          req.onsuccess = function(event) {
            let db = event.target.result;
            resolve(db);
          };
          req.onupgradeneeded = function(event) {
            let db = event.target.result;
            db.createObjectStore('kv');
          };
        } else {
          reject('IndexedDB open failed.');
        }
      } else {
        reject('IndexedDB not available.');
      }
    });
  }
  
  /**
   * Returns a promise for loading a value from our key-value store on
   * IndexedDB.
   * Falls back to local storage if IndexedDB is unavailable.
   *
   * @param {string} key The key-value pair key.
   * @return {Promise.<Object>} Promise to the key-value pair value.
   */
  function loadFromStore(key) {
    return new Promise(function(resolve, reject) {
      if (self.indexedDB) {
        let dbPromise = prepareDb_();
        dbPromise.then((db) => {
          db.onerror = (event) => reject(event);
          let get = db.transaction('kv', 'readonly').objectStore('kv').get(key);
          get.onsuccess = (event) => {
            if (event.target.result !== undefined) {
              resolve(event.target.result);
            } else {
              reject(new Error(`Key not found: ${key}`));
            }
          };
        });
      } else {
        resolve(JSON.parse(localStorage.getItem(key)));
      }
    });
  }
  
  /**
   * Returns a promise for saving a value onto our key-value store on IndexedDB.
   * Falls back to local storage if IndexedDB is unavailable.
   *
   * @param {string} key The key-value pair key.
   * @param {Object} value The key-value pair value.
   * @return {Promise} Promise to storage success.
   */
 function saveToStore(key, value) {
    return new Promise(function(resolve, reject) {
      if (self.indexedDB) {
        let dbPromise = prepareDb_();
        dbPromise.then((db) => {
          db.onerror = (event) => reject(event);
          let put = db.transaction('kv', 'readwrite')
              .objectStore('kv').put(value, key);
          put.onsuccess = () => resolve();
        });
      } else {
        localStorage.setItem(key, JSON.stringify(value));
        resolve();
      }
    });
}
if( document.querySelector('#bitstarter-calc')){
let app = new App();
}
