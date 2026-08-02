import * as monaco from 'monaco-editor/editor/editor.api';
import 'monaco-editor/features/bracketMatching/register';
import 'monaco-editor/features/clipboard/register';
import 'monaco-editor/features/codicon/register'; // https://github.com/microsoft/monaco-editor/issues/1759
import 'monaco-editor/features/contextmenu/register';
import 'monaco-editor/features/find/register';
import 'monaco-editor/features/folding/register';
import 'monaco-editor/features/gotoLine/register';
import 'monaco-editor/features/multicursor/register';
import 'monaco-editor/features/quickCommand/register';
import {useEffect, useRef} from 'preact/hooks';

type Props = Readonly<{
    text: string;
}>;

export default function DecompressorTextEditor({text}: Props) {
    const editorRef = useRef<monaco.editor.IStandaloneCodeEditor | null>(null);
    const divRef = useRef<HTMLDivElement | null>(null);

    useEffect(() => {
        if (!divRef.current) {
            return;
        }

        if (!editorRef.current) {
            editorRef.current = monaco.editor.create(divRef.current, {
                automaticLayout: true,
                lineNumbersMinChars: 8,
                renderWhitespace: 'all',
                scrollBeyondLastLine: false,
                showFoldingControls: 'always',
                // scrollbar: {
                // 	alwaysConsumeMouseWheel: false,
                // },
                theme: 'vs-dark',
            });
        }

        return () => {
            editorRef.current?.dispose();
            editorRef.current = null;
        };
    }, []);

    const previousTextRef = useRef('');
    useEffect(() => {
        if (previousTextRef.current !== text) {
            previousTextRef.current = text;
            editorRef.current?.getModel()?.setValue(text);
        }
    }, [text]);

    // Safari bugs out with `all: unset`
    return <div style={{height: '100vh'}} ref={divRef} />;
}
