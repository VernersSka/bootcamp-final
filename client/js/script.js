document
  .querySelector('.schedule_form')
  .addEventListener('submit', function (event) {
    event.preventDefault();
    request.post(this, function (response, form) {
      form.querySelector('#schedule-name').value = '';
      form.querySelector('#schedule-email').value = '';
      form.querySelector('#schedule-time').value = '';
    });
  });

document
  .querySelector('.comment_form')
  .addEventListener('submit', function (event) {
    event.preventDefault();
    request.post(this, function (response, form) {
      displayComment(response.entity.id, response.entity);
      form.querySelector('#comment-author').value = '';
      form.querySelector('#comment-text').value = '';
    });
  });

let activeState = false;

request.get('../server/api.php?api=get', function (response) {
  for (const [id, data] of Object.entries(response.entities)) {
    displayComment(id, data);
  }

  let template = document.querySelector('.template');
  template.parentNode.removeChild(template);
});

function displayComment(id, data) {
  let comment_block = document.querySelector('#comment_list');
  let template = comment_block.querySelector('.template');
  let new_comment;
  if (template === null) {
    template = comment_block.querySelector('.carousel-item');
    new_comment = template.cloneNode(true);
    new_comment.classList.remove('active');
  } else {
    new_comment = template.cloneNode(true);
    new_comment.classList.remove('template');
  }

  // piešķir 'active' klasi (vajadzīga, lai karuselis strādātu) tikai vienam komentāram
  if (activeState === false) {
    new_comment.classList.add('active');
    activeState = true;
  }

  let description = new_comment.querySelector('.contacts__comment-text');
  description.textContent = data.comment;

  let author = new_comment.querySelector('.contacts__comment-author');
  author.textContent = data.author;

  new_comment.setAttribute('data-id', id);

  comment_block.append(new_comment);
}

$('#datetimepicker').datetimepicker({
  format: 'DD/MM/YYYY, HH:mm',
  stepping: 60,
  minDate: new Date(),
  enabledHours: [9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
  daysOfWeekDisabled: [0, 6],
});

$('#datetimepicker').datetimepicker('clear');
