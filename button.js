function goBack(evt) {
    // ignore the native anchor action
    evt.preventDefault();
  
    history.back();
  }