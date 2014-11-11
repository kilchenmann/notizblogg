

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset=utf-8>
<meta name="viewport" content="width=620">
<title>HTML5 Demo: Storage</title>

</head>
<body>
<section id="wrapper">
    <header>
      <h1>Storage</h1>
    </header>

<style>
article div {
  margin: 10px 0;
}

label {
  float: left;
  display: block;
  width: 125px;
  line-height: 32px;
}
</style>
<article>
  <section>
    <div>
      <label for="session">sessionStorage:</label>
      <input type="text" name="session" value="" id="session" />
    </div>
    <div>
      <label for="local">localStorage:</label>
      <input type="text" name="local" value="" id="local" />
    </div>
    <input type="button" id="clear" value="Clear storage" />
  </section>
</article>
<script>

function getStorage(type) {
  var storage = window[type + 'Storage'],
    delta = 0,
    li = document.createElement('li');

  if (!window[type + 'Storage']) return;

  if (storage.getItem('value')) {
    delta = ((new Date()).getTime() - (new Date()).setTime(storage.getItem('timestamp'))) / 1000;

    li.innerHTML = type + 'Storage: ' + storage.getItem('value') + ' (last updated: ' + delta + 's ago)';
  } else {
    li.innerHTML = type + 'Storage is empty';
  }

  document.querySelector('#previous').appendChild(li);
}

getStorage('session');
getStorage('local');

addEvent(document.querySelector('#session'), 'keyup', function () {
  sessionStorage.setItem('value', this.value);
  sessionStorage.setItem('timestamp', (new Date()).getTime());
});

addEvent(document.querySelector('#local'), 'keyup', function () {
  localStorage.setItem('value', this.value);
  localStorage.setItem('timestamp', (new Date()).getTime());
});

addEvent(document.querySelector('#clear'), 'click', function () {
  sessionStorage.clear();
  localStorage.clear();

  document.querySelector('#previous').innerHTML = '';
  getStorage('local');
  getStorage('session');
});

var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
try {
var pageTracker = _gat._getTracker("UA-1656750-18");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>
