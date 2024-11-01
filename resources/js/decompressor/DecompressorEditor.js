// @flow

import {Component, createRef, h} from 'preact';

import MonacoEditor from 'react-monaco-editor/lib/editor';
import 'monaco-editor/esm/vs/base/browser/ui/codiconLabel/codicon/codicon.css'; // https://github.com/microsoft/monaco-editor/issues/1759

type Props = $ReadOnly<{|
    text: string,
|}>;

type MonacoEditorComponent = {
    editor: {
        layout: (dimension?: {|width: number, height: number|}) => void,
        ...
    },
    ...
};

export default class DecompressorEditor extends Component<Props> {
    monaco: {|
        current: null | MonacoEditorComponent,
    |} = createRef<MonacoEditorComponent>();

    render(): React.Node {
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

    updateDimensions: () => void = () => {
        const ref = this.monaco.current;

        if (ref) {
            ref.editor.layout();
        }
    };

    editorDidMount: () => void = () => {
        window.addEventListener('resize', this.updateDimensions);
    };

    componentWillUnmount() {
        window.removeEventListener('resize', this.updateDimensions);
    }
}
