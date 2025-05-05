export default class Round {
    name: string = '';
    author: string = '';
    notes: [string, string, string, string, string] = ['', '', '', '', ''];
    source: string = '';
    thumbnail: Buffer | null = null;
}
