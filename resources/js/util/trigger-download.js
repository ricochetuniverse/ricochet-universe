// @flow strict

import nullthrows from 'nullthrows';

export default function (href: string, filename: string) {
    // Firefox requires link to be inserted in <body> before clicking
    // https://stackoverflow.com/a/27116581
    const link = document.createElement('a');
    link.setAttribute('href', href);
    link.setAttribute('download', filename);
    link.style.position = 'absolute';
    link.style.top = '0';
    link.style.left = '-10px';
    link.style.visibility = 'hidden';
    link.setAttribute('aria-hidden', 'true');
    link.tabIndex = -1;
    nullthrows(document.body).appendChild(link);
    link.click();
    link.remove();
}
