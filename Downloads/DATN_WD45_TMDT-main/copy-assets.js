import { copyFileSync, mkdirSync, readdirSync, statSync, existsSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

function copyRecursive(src, dest) {
    if (!existsSync(dest)) {
        mkdirSync(dest, { recursive: true });
    }

    const entries = readdirSync(src, { withFileTypes: true });

    for (const entry of entries) {
        const srcPath = join(src, entry.name);
        const destPath = join(dest, entry.name);

        if (entry.isDirectory()) {
            copyRecursive(srcPath, destPath);
        } else {
            copyFileSync(srcPath, destPath);
            console.log(`Copied: ${entry.name}`);
        }
    }
}

// Copy AdminLTE CSS files only
console.log('Copying AdminLTE CSS...');
const cssFiles = ['adminlte.css', 'adminlte.css.map', 'adminlte.min.css', 'adminlte.min.css.map',
                  'adminlte.rtl.css', 'adminlte.rtl.css.map', 'adminlte.rtl.min.css', 'adminlte.rtl.min.css.map'];
const cssSrc = join(__dirname, 'resources/css');
const cssDest = join(__dirname, 'public/css');

if (!existsSync(cssDest)) {
    mkdirSync(cssDest, { recursive: true });
}

cssFiles.forEach(file => {
    const srcFile = join(cssSrc, file);
    const destFile = join(cssDest, file);
    if (existsSync(srcFile)) {
        copyFileSync(srcFile, destFile);
        console.log(`Copied CSS: ${file}`);
    }
});

// Copy AdminLTE JS files only
console.log('Copying AdminLTE JS...');
const jsFiles = ['adminlte.js', 'adminlte.js.map', 'adminlte.min.js', 'adminlte.min.js.map'];
const jsSrc = join(__dirname, 'resources/js');
const jsDest = join(__dirname, 'public/js');

if (!existsSync(jsDest)) {
    mkdirSync(jsDest, { recursive: true });
}

jsFiles.forEach(file => {
    const srcFile = join(jsSrc, file);
    const destFile = join(jsDest, file);
    if (existsSync(srcFile)) {
        copyFileSync(srcFile, destFile);
        console.log(`Copied JS: ${file}`);
    }
});

// Copy Images
console.log('Copying Images...');
if (existsSync(join(__dirname, 'resources/images'))) {
    if (!existsSync(join(__dirname, 'public/assets'))) {
        mkdirSync(join(__dirname, 'public/assets'), { recursive: true });
    }
    copyRecursive(join(__dirname, 'resources/images'), join(__dirname, 'public/assets/img'));
}

console.log('All assets copied successfully!');

