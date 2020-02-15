// @flow

import {Component, createRef, h} from 'preact';

import MonacoEditor from 'react-monaco-editor/lib/editor';

type Props = $ReadOnly<{|
    text: string,
|}>;

type MonacoEditorComponent = {
    editor: {
        layout: (dimension?: {|
            width: number,
            height: number,
        |}) => void,
        ...
    },
    ...
};

export default class DecompressorEditor extends Component<Props> {
    monaco = createRef<MonacoEditorComponent>();

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
                    ref={this.monaco}
                />
            </div>
        );
    }

    updateDimensions = () => {
        const ref = this.monaco.current;

        if (ref) {
            ref.editor.layout();
        }
    };

    editorDidMount = () => {
        window.addEventListener('resize', this.updateDimensions);
    };

    componentWillUnmount() {
        window.removeEventListener('resize', this.updateDimensions);
    }
}
