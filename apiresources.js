const XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest;
const xhr = new XMLHttpRequest();

function findRecipe(id) 
{
	const qUrl = "https://api.spoonacular.com/recipes/" + id + "/information?apiKey=fe22f905e5ec4e7b8947c5351698686c"
	xhr.open('GET', qUrl)
	xhr.onload = function () 
{
       		if (xhr.status === 200) 
		{
           		 console.log(JSON.parse(xhr.responseText))
           		 const res = JSON.parse(xhr.responseText)
           		 //displayRecipe
			 console.log(res, id)
            	}
	    	else 
		{
            		console.log(xhr.status)
        	}
    	}	
	xhr.send();
}

function findRecipesByIngredients(ingredients)
{
	const qUrl = "https://api.spoonacular.com/recipes/findByIngredients?apiKey=fe22f905e5ec4e7b8947c5351698686c&number=25&ranking=1&ingredients="

	xhr.open('GET', qUrl + ingredients)
	xhr.onload = function () 
	{
        	if (xhr.status === 200)
		{
			//displayResults function
        		console.log(JSON.parse(xhr.responseText))
			 //mapResults function
        		console.log(JSON.parse(xhr.responseText))
        	}
		else 
		{
        		console.log(xhr.status)
        	}
    	}
	xhr.send();
}
