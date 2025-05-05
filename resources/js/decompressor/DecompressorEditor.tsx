import type * as monaco from 'monaco-editor/esm/vs/editor/editor.api';
import {Component, createRef} from 'preact';
import MonacoEditor from 'react-monaco-editor';
import 'monaco-editor/esm/vs/base/browser/ui/codicons/codicon/codicon.css'; // https://github.com/microsoft/monaco-editor/issues/1759

type Props = Readonly<{
    text: string;
}>;

export default class DecompressorEditor extends Component<Props> {
    monaco = createRef<monaco.editor.IStandaloneCodeEditor>();

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
                        renderWhitespace: 'all',
                        showFoldingControls: 'always',
                    }}
                    editorDidMount={this.editorDidMount}
                    // ref={this.monaco}
                />
            </div>
        );
    }

    updateDimensions = () => {
        const ref = this.monaco.current;

        if (ref) {
            // todo broken? https://github.com/react-monaco-editor/react-monaco-editor/pull/1012
            // ref.editor.layout();
        }
    };

    editorDidMount = () => {
        window.addEventListener('resize', this.updateDimensions);
    };

    componentWillUnmount() {
        window.removeEventListener('resize', this.updateDimensions);
    }
}
