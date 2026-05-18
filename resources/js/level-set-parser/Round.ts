export default class Round {
    name: string = '';
    author: string = '';
    notes: [string, string, string, string, string] = ['', '', '', '', ''];
    source: string = '';
    thumbnail: /* Buffer on Node.js */ Uint8Array | null = null;
}
