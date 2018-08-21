import pako from 'pako';
// noinspection ES6UnusedImports
import {Component, h, render} from 'preact';

class Decompressor extends Component {
    state = {
        result: '',
    };

    render() {
        return (
            <div>
                <div className="card mb-3">
                    <div className="card-header">Decompressor</div>

                    <div className="card-body">
                        <p>Decompress Ricochet levels to view their raw data text.</p>

                        <input type="file" class="w-100" style={{cursor: 'pointer'}} onChange={this.onFileChange}/>
                    </div>
                </div>

                {this.state.result ? <div class="card mb-3">
                    <div className="card-header">Results</div>

                    <div className="card-body text-monospace">
                        <textarea value={this.state.result} class="w-100" style={{height: '100vh', tabSize: '4'}} spellcheck="false"/>
                    </div>
                </div> : null}
            </div>
        );
    }

    onFileChange = ({currentTarget}) => {
        if (currentTarget.files && currentTarget.files[0]) {
            this.processFile(currentTarget.files[0]);
        }
    };

    processFile = (file) => {
        // should be unknown
        if (file.type !== '') {
            throw new Error('File should be .RicochetI or .RicochetLW');
        }

        const reader = new FileReader();
        reader.onload = this.onFileReaderFile;
        reader.onerror = (ex) => {
            throw ex;
        };
        reader.readAsArrayBuffer(file);
    };

    onFileReaderFile = (buffer) => {
        const compressed = new Uint8Array(buffer.currentTarget.result, 9);
        const result = pako.inflate(compressed, {to: 'string'});

        this.setState({result});
    };
}

const root = document.getElementById('decompressor-root');

if (root) {
    render(<Decompressor/>, root);
}
