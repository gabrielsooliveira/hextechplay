const fs = require('fs');
const path = require('path');

const translations = JSON.parse(fs.readFileSync(__dirname + '/translations.json', 'utf-8'));

function resolveDotPath(obj, path) {
    return path.split('.').reduce((o, i) => o ? o[i] : null, obj);
}

function getTranslation(key) {
    for (const group in Object.keys(translations)) {
        let val = resolveDotPath(translations[Object.keys(translations)[group]], key);
        if (val) return val;
    }
    return null;
}

function processDirectory(dir) {
    const files = fs.readdirSync(dir);
    for (const file of files) {
        const fullPath = path.join(dir, file);
        if (fs.statSync(fullPath).isDirectory()) {
            processDirectory(fullPath);
        } else if (fullPath.endsWith('.vue')) {
            let content = fs.readFileSync(fullPath, 'utf-8');
            let originalContent = content;
            
            // Remove vue-i18n imports
            content = content.replace(/import\s*\{\s*useI18n\s*\}\s*from\s*['"]vue-i18n['"];?/g, '');
            content = content.replace(/const\s*\{\s*t\s*\}\s*=\s*useI18n\(\);?/g, '');
            
            // Replace t('something')
            content = content.replace(/\bt\(\s*['"`]([^'"`$]+)['"`]\s*\)/g, (match, key) => {
                const text = getTranslation(key);
                if (text) {
                    return `"${text.replace(/"/g, '\\"')}"`;
                }
                return `"${key}"`; // fallback to key
            });

            if (content !== originalContent) {
                fs.writeFileSync(fullPath, content, 'utf-8');
                console.log('Fixed imports & t(): ' + fullPath);
            }
        }
    }
}

processDirectory(path.join(__dirname, 'resources', 'js', 'Pages'));
processDirectory(path.join(__dirname, 'resources', 'js', 'Components'));
