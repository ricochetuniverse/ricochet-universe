import {Component, h} from 'preact';

import MonacoEditor from 'react-monaco-editor/lib/editor';

export default class DecompressorEditor extends Component {
    monaco = null;

    render() {
        // Safari bugs out with `all: unset`
        return (
            <div style={{height: '100vh'}}>
                <MonacoEditor
                    height="100%"
                    theme="vs-dark"
                    value={this.props.text}
                    options={{
                        lineNumbersMinChars: 8,
                        renderControlCharacters: true,
                        renderWhitespace: 'all',
                        showFoldingControls: 'always',
                    }}
                    editorDidMount={this.editorDidMount}
                    ref={(ref) => {
                        this.monaco = ref;
                    }}
                />
            </div>
        );
    }

    updateDimensions = () => {
        this.monaco.editor.layout();
    };

    editorDidMount = () => {
        window.addEventListener('resize', this.updateDimensions);
    };

    componentWillUnmount() {
        window.removeEventListener('resize', this.updateDimensions);
    }
}
