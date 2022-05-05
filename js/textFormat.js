function filterText(text) {
  let filteredText;
  // SPECIAL TEXT 1
  filteredText = text;
  filteredText = filteredText.replaceAll(
    "==>",
    "<i class='fi fi-rr-arrow-right'>"
  );
  filteredText = filteredText.replaceAll("/!!", "</span>");
  filteredText = filteredText.replaceAll("/**", "</span>");
  filteredText = filteredText.replaceAll("/$", "</span>");
  filteredText = filteredText.replaceAll("/#", "</span>");
  filteredText = filteredText.replaceAll("/%", "</span>");
  filteredText = filteredText.replaceAll("/^", "</span>");
  filteredText = filteredText.replaceAll("/&", "</span>");
  filteredText = filteredText.replaceAll("/@", "</span>");
  filteredText = filteredText.replaceAll("/?", "</span>");
  filteredText = filteredText.replaceAll("/]", "</span>");

  filteredText = filteredText.replaceAll("___", "<div class='empty'></div>");

  filteredText = filteredText.replaceAll("!!", "<span class='special-text-1'>");
  filteredText = filteredText.replaceAll("**", "<span class='special-text-2'>");
  filteredText = filteredText.replaceAll("$", "<span class='special-text-3'>");
  filteredText = filteredText.replaceAll("#", "<span class='special-text-4'>");
  filteredText = filteredText.replaceAll("%", "<span class='special-text-5'>");
  filteredText = filteredText.replaceAll("^", "<span class='special-text-6'>");
  filteredText = filteredText.replaceAll("&", "<span class='special-text-7'>");
  filteredText = filteredText.replaceAll("@", "<span class='special-text-8'>");
  filteredText = filteredText.replaceAll("?", "<span class='special-text-9'>");
  filteredText = filteredText.replaceAll("[", "<span class='special-text-10'>");
  // REPLACE LINE BREAKS
  filteredText = filteredText.replaceAll("\n", "<br />");
  filteredText = filteredText.replaceAll("\r\n", "<br />");

  return filteredText;
}
