class WvParking {
    constructor(p) {
        this.parkingSpots = p.parkingSpots;
        this.bookedSlots = p.bookedSlots;

        this.componentName = p.componentName;
        this.wid = p?.wid;

        this.startTime = p?.startTime ?? 8;
        this.endTime = p?.endTime ?? 18;
        this.interval = p?.interval ?? 1;

        this.fixedHeader = $('#fixedHeader');
        this.parkingCalendar = $('#parkingCalendar');
        this.init();
    }

    init() {
        this.renderTable();
        this.renderFixedHead();

        this.handlers();
    }

    handlers() {
        let app = this;

        app.parkingCalendar.datepicker({
            onSelect: function () {
                app.refresh();
            }
        });

        $(window).on('resize', function () {
            app.syncFixedHeadWidth();
        });

        $(window).on('scroll', function () {
            let tableContainer = $('.table-container'),
                scrollTop = $(window).scrollTop(),
                tableTop = tableContainer.offset().top,
                tableBottom = tableTop + tableContainer.height();

            if (scrollTop >= tableTop && scrollTop <= tableBottom) {
                app.fixedHeader.css({
                    display: 'block',
                    top: 0,
                    left: tableContainer.offset().left
                });
            } else {
                app.fixedHeader.hide();
            }
        });

        $('#citiChoose').change(function () {
            app.refresh();
        });
    }

    refresh()
    {
        let app = this;

        BX.ajax.runComponentAction(
            `${app.componentName}`,
            'refresh',
            {
                mode: 'class',
                data: {
                    cityId: $('#citiChoose').val(),
                    date: app.formatDate(app.parkingCalendar.datepicker('getDate'))
                },
            }
        ).then(function (result) {
            let data = result.data;

            app.parkingSpots = data.SPOTS;
            app.bookedSlots = data.RESERVATIONS;

            $('#headerRow').empty();
            $('#tableBody').empty();
            $('#fixedHeader').empty();

            app.renderTable();
            app.renderFixedHead();
        }, function (e) {
            console.log(e);
        });
    }

    renderTable() {
        let app = this;

        let headerRow = $('#headerRow');

        headerRow.append('<th>Время</th>');
        app.parkingSpots.forEach(spot => {
            headerRow.append(`<th>${spot.NAME}</th>`);
        });

        let tableBody = $('#tableBody');

        for (let hour = app.startTime; hour <= app.endTime; hour += app.interval) {
            let row = $('<tr></tr>');
            row.append(`<td>${hour}:00</td>`);

            app.parkingSpots.forEach(spot => {
                let bookedSlots = app.bookedSlots || [];
                let bookedSlot = bookedSlots?.find(slot => slot.spot === spot.ID && hour >= slot.start && hour < slot.end);

                if (bookedSlot && hour === Number(bookedSlot.start)) {
                    let duration = bookedSlot.end - bookedSlot.start;

                    let cell = $('<td></td>')
                        .addClass('booked')
                        .attr('rowspan', duration);

                    let cellContainer = $('<div>', {
                        class: 'cell-container'
                    });

                    let cellClient = $('<div>', {
                        class: 'cell-client',
                    }).append(`<a href="/crm/${bookedSlot.client.entityId}/details/${bookedSlot.client.id}/">Пациент</a>`);

                    cellClient.appendTo(cellContainer);

                    let cellInfo = $('<div>', {
                        class: 'cell-info',
                        text: `${bookedSlot.car.CAR_BRAND} ${bookedSlot.car.CAR_NUMBER}`
                    });

                    cellInfo.appendTo(cellContainer);

                    cellClient.append(`<span data-hint="Время брони: ${bookedSlot.start}:00 - ${bookedSlot.end}:00 (${bookedSlot.end - bookedSlot.start}ч)<br> Комментарий: ${bookedSlot.comment}" data-hint-html></span>`)

                    cellContainer.appendTo(cell);

                    cell.on('click', function (event) {
                        if (event.target.tagName === 'A') {
                            return;
                        }

                        Object.assign(bookedSlot, {
                            selectedTime: hour,
                        });

                        app.openSlider(bookedSlot);
                    });

                    row.append(cell);
                } else if (!bookedSlot) {
                    let cell = $('<td></td>');

                    cell.on('click', function () {

                        let params = {
                            selectedTime: hour,
                            spotId: spot.ID
                        }

                        if (app?.wid) {
                            params['client'] = app?.wid;
                        }
                        app.openSlider(params);
                    });

                    row.append(cell);
                }
            });

            tableBody.append(row);
        }

        BX.UI.Hint.init(BX('parkingTable'));
    }

    openSlider(params = {})
    {
        let app = this;

        Object.assign(params, {
            cityId: $('#citiChoose').val(),
            date: app.formatDate(app.parkingCalendar.datepicker('getDate'))
        });

        BX.SidePanel.Instance.open(
            '/wv_parking/edit/',
            {
                width: 500,
                cacheable: false,
                allowChangeHistory: false,
                requestMethod: 'post',
                requestParams: {
                    params: params,
                },
                events: {
                    onClose: function (event) {
                        app.refresh();
                        //event.getSlider().destroy();
                    }
                }
            }
        );
    }

    renderFixedHead() {
        this.fixedHeader.html($('#headerRow').clone().html());
        this.syncFixedHeadWidth();
    }

    syncFixedHeadWidth() {
        let app = this;
        $('#headerRow th').each(function (index) {
            app.fixedHeader.find('th').eq(index).outerWidth($(this).outerWidth());
        });
    }

    formatDate(dateString) {
        let date = new Date(dateString);
        let day = String(date.getDate()).padStart(2, '0');
        let month = String(date.getMonth() + 1).padStart(2, '0');
        let year = date.getFullYear();

        return `${day}.${month}.${year}`;
    }
}