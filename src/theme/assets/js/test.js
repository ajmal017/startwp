var socketUrl = new URL('/','http://rpn.javascript.ninja');
socketUrl.protocol = 'ws';
socketUrl.port = '8080';

const operators = {
    '+': (x, y) => x + y,
    '-': (x, y) => x - y,
    '*': (x, y) => x * y,
    '/': (x, y) => x / y
};

let evaluate = (expr) => {
    let stack = [];
    
    expr.split(' ').forEach((token) => {
        if (token in operators) {
            let [y, x] = [stack.pop(), stack.pop()];
            stack.push(operators[token](x, y));
        } else {
            stack.push(parseFloat(token));
        }
    });

    return stack.pop();
};

var ws = new WebSocket(socketUrl.href);

ws.addEventListener('message', function(event) {
    ws.send(evaluate(event.data));
    console.log(event)
});
