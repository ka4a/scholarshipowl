// Create new form
var form = new FormData();

// Set fields data
form.append('data[attributes][name]', 'Full name');
form.append('data[attributes][email]', 'email@dot.me');
form.append('data[attributes][phone]', '+1 111 111111');

// Set requirements data
form.append('data[attributes][requirements][781]', 'Text inputed by user');
form.append('data[attributes][requirements][782]', files[0]);
