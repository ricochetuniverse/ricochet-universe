// noinspection ES6UnusedImports
import {Component, h} from 'preact';

import MonacoEditor from 'react-monaco-editor';

export default class DecompressorEditor extends Component {
    render() {
        return (
            <div style={{all: 'unset', height: '100vh'}}>
                <MonacoEditor
                    height="100%"
                    theme="vs-dark"
                    value={this.props.text}
                    options={{
                        lineNumbersMinChars: 7,
                        renderControlCharacters: true,
                        renderWhitespace: 'all',
                        showFoldingControls: 'always',
                    }}
                />
            </div>
        );
    }
}
