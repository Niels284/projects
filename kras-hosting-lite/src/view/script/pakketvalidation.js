const warnings = [];

window.onload = function () {
  const btn = document.querySelector('button');
  btn.addEventListener('click', validateInputs);
}

function validateInputs(e) {
  warnings.forEach(warning => {
    warning.remove();
  });
  const inputs = this.form.querySelectorAll('input');
  const radioEl = this.form.querySelector('#radio1');
  const packetType = radioEl.checked;
  for (let i = 0; i < inputs.length; i++) {
    const input = inputs[i];
    input.style.borderColor = '#b9b9b9';
    if ((!packetType && (input.name == 'pakketProcessors' || input.name == 'pakketMemory')) || (packetType && (input.name == 'pakketDatabases' || input.name == 'pakketMail'))) {
      continue;
    }
    let warn = '';
    if (input.name !== 'hostType' && input.value.trim() == '') {
        warn = "Waarde is verplicht.";
    }
    if (warn) {
      cancelSubmit(e, input, warn);
    }
  }
}

function cancelSubmit(event, input, warning) {
  let warnEl = document.createElement('span');
  warnEl.classList.add('input-warn');
  warnEl.innerHTML = warning;
  warnEl.style.top = input.offsetTop - 20 + 'px';
  input.after(warnEl);
  warnings.push(warnEl);
  input.style.borderColor = '#ff0000';
  event.preventDefault();
}
