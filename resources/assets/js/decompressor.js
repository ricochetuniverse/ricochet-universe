import pako from 'pako';
// noinspection ES6UnusedImports
import {Component, h, render} from 'preact';

import MonacoEditor from 'react-monaco-editor';

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

                        <input type="file" class="w-100" style={{cursor: 'pointer'}} accept=".RicochetI,.RicochetLW"
                               onChange={this.onFileChange}/>
                    </div>
                </div>

                {this.state.result ? <div class="card mb-3">
                    <div className="card-header">Results</div>

                    <div style={{all: 'unset', height: '100vh'}}>
                        <MonacoEditor
                            height="100%"
                            theme="vs-dark"
                            value={this.state.result}
                            options={{
                                lineNumbersMinChars: 7,
                                renderControlCharacters: true,
                                renderWhitespace: 'all',
                                showFoldingControls: 'always',
                            }}
                        />
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
        const decoder = new TextDecoder('windows-1252', {fatal: true});

        const result = decoder.decode(pako.inflate(compressed));

        this.setState({result});
    };
}

const root = document.getElementById('decompressor-root');

if (root) {
    render(<Decompressor/>, root);
}
