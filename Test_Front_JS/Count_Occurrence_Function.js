//"On cherche à calculer le nombre d'occurence de chaque mots dans un texte. Les mots de moins de deux lettres seront ignorés. Concevez un module JavaScript qui répond à ce problème. Dans le doute, utilisez votre bon sens.”

//create a javascript module
(function(){
  var string = "Nice to meet you Evanoes team"
  //create a showOccurence function
  function showOccurence(){

    //replace all sympoles by ""; separate each letter (1 or + space); apply the function on all elements of the table
    var count = string.replace("/[^\w\s]/g","").split(/\s+/).reduce(function(map, word){
//a object: key=>word, value=>occurence, if find the word once=>1 twice=>1+1... 
        map[word] = (map[word]||0)+1;
        return map;
//create a object to stock all inside
    },Object.create(null));
    return count;
  }
  console.log(showOccurence());


}());
