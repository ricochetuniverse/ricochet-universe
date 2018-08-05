import $ from 'jquery';
// noinspection ES6UnusedImports
import {h, render} from 'preact';

$('.roundInfo__link').on('click', function (ev) {
    ev.preventDefault();

    const $link = $(this);

    const options = {
        name: $link.data('round-name'),
        author: $link.data('round-author'),
        note1: $link.data('round-note-1'),
        note2: $link.data('round-note-2'),
        note3: $link.data('round-note-3'),
        note4: $link.data('round-note-4'),
        note5: $link.data('round-note-5'),
        source: $link.data('round-source'),
        imageUrl: $link.data('round-image-url'),
    };

    const $dialog = $('<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="levelInfoModalTitle" aria-hidden="true"></div>');
    $dialog.on('hidden.bs.modal', () => {
        render('', reactDialog, reactDialog.parentElement);

        $dialog.remove();
    });

    const fragment = document.createDocumentFragment();
    const reactDialog = render(getModal(options), fragment);
    $dialog.append(fragment);

    $(document.body).append($dialog);
    $dialog.modal();
});

function getModal(options) {
    return <div className="modal-dialog" role="document">
        <div className="modal-content">
            <div className="modal-header">
                <h5 className="modal-title" id="levelInfoModalTitle">{options.name}</h5>

                <button type="button" className="close" data-dismiss="modal" title="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div className="modal-body">
                <img
                    src={options.imageUrl}
                    alt={'Screenshot of “' + options.name + '”'} width="105" height="80"
                    className="d-block mx-auto mb-3"/>

                <div className="row">
                    {/* Preact doesn't support fragments yet :( */}
                    {options.author ? <div className="col-auto">Author:</div> : null}
                    {options.author ? <div className="col">{options.author}</div> : null}

                    <div className="w-100"/>
                    {options.note1 ? <div className="col-auto">Note 1:</div> : null}
                    {options.note1 ? <div className="col">{options.note1}</div> : null}

                    <div className="w-100"/>
                    {options.note2 ? <div className="col-auto">Note 2:</div> : null}
                    {options.note2 ? <div className="col">{options.note2}</div> : null}

                    <div className="w-100"/>
                    {options.note3 ? <div className="col-auto">Note 3:</div> : null}
                    {options.note3 ? <div className="col">{options.note3}</div> : null}

                    <div className="w-100"/>
                    {options.note4 ? <div className="col-auto">Note 4:</div> : null}
                    {options.note4 ? <div className="col">{options.note4}</div> : null}

                    <div className="w-100"/>
                    {options.note5 ? <div className="col-auto">Note 5:</div> : null}
                    {options.note5 ? <div className="col">{options.note5}</div> : null}

                    <div className="w-100"/>
                    {options.source ? <div className="col-auto">Source:</div> : null}
                    {options.source ? <div className="col">{options.source}</div> : null}
                </div>
            </div>

            <div className="modal-footer">
                <button type="button" className="btn btn-outline-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>;
}
