if (navigator.serviceWorker) {


// I'm not a fan of depending on SW for page render
// but it I don't think there's another way around
// it here.
navigator.serviceWorker.register(`${IondigitalThemeParams.templateUrl}/assets/js/sw.php`, {scope: '/'});
navigator.serviceWorker.ready.then(function() {
  console.log('ready');
});
}

