import pako from 'pako';
// noinspection ES6UnusedImports
import {Component, h} from 'preact';
import Loadable from 'react-loadable';

import LoadingComponent from './LoadingComponent';

const LoadableDecompressorEditor = Loadable({
    loader: () => import('./DecompressorEditor'),
    loading(props) {
        return (
            <div className="card-body">
                <LoadingComponent {...props} text="Loading text editor..." />
            </div>
        );
    },
    timeout: 10000,
});

export default class DecompressorApp extends Component {
    state = {
        result: '',
    };

    render() {
        return (
            <div>
                <div className="card mb-3">
                    <div className="card-header">Decompressor</div>

                    <div className="card-body">
                        <p>Decompress Ricochet levels to view their raw text data.</p>

                        <input type="file" class="w-100" style={{cursor: 'pointer'}} accept=".RicochetI,.RicochetLW"
                               onChange={this.onFileChange} onMouseEnter={this.onBrowseButtonMouseOver}/>
                    </div>
                </div>

                {this.state.result ? <div class="card mb-3">
                    <div className="card-header">Results</div>

                    <LoadableDecompressorEditor text={this.state.result} />
                </div> : null}
            </div>
        );
    }

    onBrowseButtonMouseOver() {
        LoadableDecompressorEditor.preload();
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
