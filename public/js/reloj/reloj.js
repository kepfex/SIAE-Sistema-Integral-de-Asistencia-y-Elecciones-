window.addEventListener("DOMContentLoaded",() => {
    actualizarFecha();
    const intervalo = setInterval(actualizarFecha(), 1000);
    
    var clock = new BouncyBlockClock(".clock");
});

var actualizarFecha = ()=> {
    // Obtenemos la fecha actual
    const fecha = new Date();
    const diaSemana = fecha.getDay(),
    dia = fecha.getDate(),
    mes = fecha.getMonth(),
    year = fecha.getFullYear();
    // Accedemos a los elementos del DOM para agregar mas adelante sus correspondientes valores
		var pDiaSemana = document.getElementById('diaSemana'),
        pDia = document.getElementById('dia'),
        pMes = document.getElementById('mes'),
        pYear = document.getElementById('year');
    // Obtenemos el dia se la semana y lo mostramos
		var semana = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
		pDiaSemana.textContent = semana[diaSemana]+", ";

		// Obtenemos el dia del mes
		pDia.textContent = dia;

		// Obtenemos el Mes y año y lo mostramos
		var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
		pMes.textContent = meses[mes];
		pYear.textContent = year;
}

class BouncyBlockClock {
    constructor(qs) {
        this.el = document.querySelector(qs);
        this.time = { a: [], b: [] };
        this.rollClass = "clock__block--bounce";
        this.digitsTimeout = null;
        this.rollTimeout = null;
        this.mod = 0 * 60 * 1000;

        this.loop();
    }
    animateDigits() {
        const groups = this.el.querySelectorAll("[data-time-group]");

        Array.from(groups).forEach((group,i) => {
            const { a, b } = this.time;

            if (a[i] !== b[i]) group.classList.add(this.rollClass);
        });

        clearTimeout(this.rollTimeout);
        this.rollTimeout = setTimeout(this.removeAnimations.bind(this),900);
    }
    displayTime() {
        // screen reader time
        const timeDigits = [...this.time.b];
        const ap = timeDigits.pop();

        this.el.ariaLabel = `${timeDigits.join(":")} ${ap}`;

        // displayed time
        Object.keys(this.time).forEach(letter => {
            const letterEls = this.el.querySelectorAll(`[data-time="${letter}"]`);

            Array.from(letterEls).forEach((el,i) => {
                el.textContent = this.time[letter][i];
            });
        });
    }
    loop() {
        this.updateTime();
        this.displayTime();
        this.animateDigits();
        this.tick();
    }
    removeAnimations() {
        const groups = this.el.querySelectorAll("[data-time-group]");
    
        Array.from(groups).forEach(group => {
            group.classList.remove(this.rollClass);
        });
    }
    tick() {
        clearTimeout(this.digitsTimeout);
        this.digitsTimeout = setTimeout(this.loop.bind(this),1e3);    
    }
    updateTime() {
        const rawDate = new Date();
        const date = new Date(Math.ceil(rawDate.getTime() / 1e3) * 1e3 + this.mod);
        let h = date.getHours();
        const m = date.getMinutes();
        const s = date.getSeconds();
        const ap = h < 12 ? "AM" : "PM";

        if (h === 0) h = 12;
        if (h > 12) h -= 12;

        this.time.a = [...this.time.b];
        this.time.b = [
            (h < 10 ? `0${h}` : `${h}`),
            (m < 10 ? `0${m}` : `${m}`),
            (s < 10 ? `0${s}` : `${s}`),
            ap
        ];

        if (!this.time.a.length) this.time.a = [...this.time.b];
    }
}