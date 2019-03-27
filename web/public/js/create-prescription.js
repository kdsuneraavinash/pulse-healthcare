let medVue = new Vue(
    {
        el: '.med',
        data: {
            medCards: [
                {
                    name: '',
                    dose: '',
                    frequency: '',
                    time: '',
                    comment: ''
                }
            ]
        },
        methods: {
            addMedCard() {
                this.medCards.push({
                    name: '',
                    dose: '',
                    frequency: '',
                    time: '',
                    comment: ''
                })
            },
            removeMedCard(index) {
                this.medCards.splice(index, 1)
            },
        }
    }
);

function submitForm() {
    $.ajax({
        type: "POST",
        url: '{{ site }}/control/doctor/create/prescription',
        data: {
            patient: $("#id").val(),
            date: $("#date").val(),
            medications: medVue.medCards,
        },
        dataType: 'html',
        success: function () {

        }
    });
}
