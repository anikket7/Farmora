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

const replacements = [
    [/bg-\[#f5f2ec\]/g, 'bg-surface-lighter'],
    [/text-\[#1a3c2a\]/g, 'text-primary-dark'],
    [/text-\[#9ca3af\]/g, 'text-text-light'],
    [/border-\[#e5e2dc\]/g, 'border-border'],
    [/text-\[#2d6a4f\]/g, 'text-primary'],
    [/hover:text-\[#1a3c2a\]/g, 'hover:text-primary-dark'],
    [/text-\[#1a1a1a\]/g, 'text-text'],
    [/text-\[#6b7280\]/g, 'text-text-muted'],
    [/\bflex-grow\b/g, 'grow'],
    [/\bsm:flex-grow-0\b/g, 'sm:grow-0'],
    [/\bbg-gradient-to-t\b/g, 'bg-linear-to-t'],
    [/\bbg-gradient-to-b\b/g, 'bg-linear-to-b'],
    [/\bbg-gradient-to-r\b/g, 'bg-linear-to-r'],
    [/_var\(--tw-gradient-stops\)/g, 'var(--tw-gradient-stops)'],
    [/\bfont-label-caps font-mono\b/g, 'font-label-caps'],
    [/\bblock flex\b/g, 'flex'],
    [/\brounded-full border-transparent\b/g, 'rounded-full border-white/5'],
    [/\brounded-none border-white\/5\b/g, 'rounded-full border-white/5'] // Simplify conflicting classes
];

const files = walk('resources/views');
let updatedCount = 0;

files.forEach(file => {
    let content = fs.readFileSync(file, 'utf8');
    let original = content;
    
    replacements.forEach(([pattern, replacement]) => {
        content = content.replace(pattern, replacement);
    });
    
    // Some specific manual fixes for conflicting classes
    content = content.replace(/class="([^"]*)\bblock\b([^"]*)\bflex\b([^"]*)"/g, (match, p1, p2, p3) => {
        return `class="${p1}flex${p2}${p3}"`;
    });
    content = content.replace(/class="([^"]*)\bfont-label-caps\b([^"]*)\bfont-mono\b([^"]*)"/g, (match, p1, p2, p3) => {
        return `class="${p1}font-label-caps${p2}${p3}"`;
    });
    content = content.replace(/class="([^"]*)\bfont-mono\b([^"]*)\bfont-label-caps\b([^"]*)"/g, (match, p1, p2, p3) => {
        return `class="${p1}font-label-caps${p2}${p3}"`;
    });

    if (original !== content) {
        fs.writeFileSync(file, content);
        console.log('Updated ' + file);
        updatedCount++;
    }
});

console.log(`Total files updated: ${updatedCount}`);
