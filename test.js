
const fs = require('mz/fs');
// Example when handled through fs.watch listener
fs.watch('./src', { recursive : true }, (eventType, filename) => {
    if (filename) {
        console.log(filename);
        console.log(filename);
        // Prints: <Buffer ...>
    }
});