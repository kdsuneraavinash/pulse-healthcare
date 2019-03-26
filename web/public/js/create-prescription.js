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
    let data = {
        patientNIC: document.getElementById('patientNIC').value,
        date: document.getElementById('date').value,
        medCards: medVue.medCards,
    };

    $.ajax({
        type: "POST",
        url: '{{ site }}/control/doctor/create/prescription',
        data: {data: data},
        dataType: 'html'

    });
}
