const fs = require('fs');
const path = require('path');

function walk(dir) {
    let results = [];
    const list = fs.readdirSync(dir);
    list.forEach(function(file) {
        file = path.join(dir, file);
        const stat = fs.statSync(file);
        if (stat && stat.isDirectory()) {
            results = results.concat(walk(file));
        } else if (file.endsWith('.blade.php')) {
            results.push(file);
        }
    });
    return results;
}

const files = walk('resources/views');
files.forEach(file => {
    let content = fs.readFileSync(file, 'utf8');
    let original = content;
    // Replace old lime green and neon green rgb values with gold
    content = content.replace(/195,\s*244,\s*0/g, '203,163,88')
                     .replace(/204,\s*255,\s*0/g, '203,163,88')
                     .replace(/197,\s*239,\s*173/g, '6,205,172'); // old primary to teal
    if (original !== content) {
        fs.writeFileSync(file, content);
        console.log('Updated ' + file);
    }
});
