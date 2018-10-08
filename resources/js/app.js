import $ from 'jquery'

function setup () {
    setupMovieShowingTable()
    setupDialogs()

    showDialog('register')
}

function setupMovieShowingTable () {
    const $table = $('#movie-showing-table')
    if (!$table.length) {
        return
    }

    const $dateColumns = $table.find('thead > tr > th').slice(1)

    $('.time-list > li').on('click', (e) => {
        const $item = $(e.currentTarget)
        const $column = $item.parents('td')
        const $row = $column.parent()

        const $columns = $row.children()
        const dateIndex = $columns.index($column) - 1

        showDialog('movie', {
            'showingId': $item.data('showing'),
            'movieTitle': $columns[0].innerText,
            'time': $item.children()[0].innerText,
            'date': $dateColumns[dateIndex].innerText,
        })
    })
}

function setupDialogs () {
    const $dialogWrappers = $('.dialog-wrapper')
    $dialogWrappers.on('click', (e) => {
        if (e.currentTarget === e.target) {
            $dialogWrappers.removeClass('is-open');
        }
    })
}

function showDialog (id, variables) {
    const $dialog = $(`#dialog_${id}`)
    if (!$dialog.length) {
        return
    }

    for (const variableName in variables) {
        const $element = $dialog.find(`[data-var="${variableName}"]`);
        const value = variables[variableName];
        if ($element.is('input')) {
            $element.val(value);
        } else {
            $element.text(value);
        }
    }

    $dialog.addClass('is-open')
}

setup()
