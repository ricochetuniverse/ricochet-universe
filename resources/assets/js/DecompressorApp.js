import {inflate} from 'pako/lib/inflate';
// noinspection ES6UnusedImports
import {Component, h} from 'preact';
import Loadable from 'react-loadable';
import {Card, CardBody, CardHeader} from 'reactstrap';

import LoadingComponent from './LoadingComponent';

const LoadableDecompressorEditor = Loadable({
    loader: () => import('./DecompressorEditor'),
    loading(props) {
        return (
            <CardBody>
                <LoadingComponent {...props} text="Loading text editor..."/>
            </CardBody>
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
                <Card className="mb-3">
                    <CardHeader>Decompressor</CardHeader>

                    <CardBody>
                        <p>Decompress Ricochet levels to view their raw text data.</p>

                        <input type="file" className="w-100" style={{cursor: 'pointer'}} accept=".RicochetI,.RicochetLW"
                               onChange={this.onFileChange} onMouseEnter={this.onBrowseButtonMouseOver}/>
                    </CardBody>
                </Card>

                {this.state.result ? <Card className="mb-3">
                    <CardHeader>Results</CardHeader>

                    <LoadableDecompressorEditor text={this.state.result}/>
                </Card> : null}
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

        const result = decoder.decode(inflate(compressed));

        this.setState({result});
    };
}
