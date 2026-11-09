import Breadcrumb from 'react-bootstrap/Breadcrumb';

import type {ParsedPath} from './parsePath';

type Props = Readonly<{
    parsed: ParsedPath;
    urlPrefix: string;
}>;

export default function NavBreadcrumb({parsed, urlPrefix}: Props) {
    const directories = parsed.dirname.split('/').filter(Boolean);

    const inFileView = 'filename' in parsed;

    return (
        <Breadcrumb>
            <Breadcrumb.Item
                href={urlPrefix + '/'}
                active={parsed.dirname === '/' && !inFileView}
            >
                Data.dat
            </Breadcrumb.Item>

            {directories.map((item, index) => {
                const last = !inFileView && index === directories.length - 1;

                return (
                    <Breadcrumb.Item
                        key={index.toString() + '/' + item}
                        href={
                            !last
                                ? urlPrefix +
                                  '/' +
                                  directories.slice(0, index + 1).join('/') +
                                  '/'
                                : undefined
                        }
                        active={last}
                    >
                        {item}
                    </Breadcrumb.Item>
                );
            })}

            {inFileView ? (
                <Breadcrumb.Item active>{parsed.filename}</Breadcrumb.Item>
            ) : null}
        </Breadcrumb>
    );
}
