import {useRef} from 'preact/hooks';
import MonacoEditor, {type MonacoEditorHandle} from 'react-monaco-editor';

import 'monaco-editor/esm/vs/base/browser/ui/codicons/codicon/codicon.css'; // https://github.com/microsoft/monaco-editor/issues/1759

type Props = Readonly<{
    text: string;
}>;

export default function DecompressorTextEditor(props: Props) {
    const monacoRef = useRef<MonacoEditorHandle>(null);

    function updateDimensions() {
        monacoRef.current?.editor.layout();
    }

    // Safari bugs out with `all: unset`
    return (
        <div style={{height: '100vh'}}>
            <MonacoEditor
                width="100%"
                height="100%"
                theme="vs-dark"
                value={props.text}
                options={{
                    lineNumbersMinChars: 8,
                    renderWhitespace: 'all',
                    showFoldingControls: 'always',
                }}
                ref={monacoRef}
                editorDidMount={() => {
                    window.addEventListener('resize', updateDimensions);
                }}
                editorWillUnmount={() => {
                    window.removeEventListener('resize', updateDimensions);
                }}
            />
        </div>
    );
}
