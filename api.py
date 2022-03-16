import requests, sys, os, json

api_key = "cb8cebfd5d1d4e1b88f4f7e9e4f95f8a"

#search recipes by keyword
def complexRecipeSearch(request):
	if request.method == 'POST':
		recipe = request.POST.get('recipe', None)
		response = requests.get("https://api.spoonacular.com/recipes/complexSearch?query={recipe}&apiKey={api_key}&number=9")
		recipe_data = response.json()
	return recipe_data

#get recipe details with nutritional facts
def recipeDetails(request,id):
	if request.method == 'GET':
		id =id
		reponse = request.get('https://api.spoonacular.com/recipes/{id}/information?includeNutrition=true&apiKey={api_key}')
		recipe_data = response.json()
	return recipe_data


