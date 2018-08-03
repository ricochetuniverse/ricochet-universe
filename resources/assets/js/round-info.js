import $ from 'jquery';
// noinspection ES6UnusedImports
import {h, render} from 'preact';

$('.roundInfo__link').on('click', function (ev) {
    ev.preventDefault();

    const link = this;

    const options = {
        name: link.dataset['roundName'],
        author: link.dataset['roundAuthor'],
        note1: link.dataset['roundNote-1'],
        note2: link.dataset['roundNote-2'],
        note3: link.dataset['roundNote-3'],
        note4: link.dataset['roundNote-4'],
        note5: link.dataset['roundNote-5'],
        source: link.dataset['roundSource'],
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
