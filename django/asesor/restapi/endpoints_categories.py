from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
from .models import Tcategory, Tuser
from django.core.exceptions import ObjectDoesNotExist
import json

@csrf_exempt
def categorias_por_user_id(request, id):
    try:
        user_id = Tuser.objects.get(id=id)
    except:
        # Error 404: La petición ha fallado porque el ID de usuario especificado no existe
        return JsonResponse(status=404, safe=False, data=[])
    try:
        token = request.headers['token']
    except:
        # Error 401: La petición ha fallado porque no se ha enviado el token de autenticación en las cabeceras
        return JsonResponse(status=401, safe=False, data=[])
    try:
        user = Tuser.objects.get(id=id, active_session_token=token)
    except:
        # Error 403: La petición ha fallado porque el token de autenticación no es válido o no coincide con el ID de usuario pasado por parámetro en la ruta
        return JsonResponse(status=403, safe=False, data=[])
    if (request.method == 'GET'):
        categorias = Tcategory.objects.filter(author_id=id)
        lista_categorias = []
        for fila_categoria_sql in categorias:
            diccionario = {}
            diccionario['id'] = fila_categoria_sql.id
            diccionario['name'] = fila_categoria_sql.name
            diccionario['author_id'] = fila_categoria_sql.author_id
            lista_categorias.append(diccionario)
        return JsonResponse(lista_categorias, json_dumps_params={'ensure_ascii': False}, safe=False)
    elif (request.method == 'POST'):
        categorias = Tcategory()
        try:
            json_peticion = json.loads(request.body)
            categorias.name = json_peticion['name']
        except:
            # Error 400: La petición ha fallado porque 'name' no está o es inválido
            return JsonResponse(status=400, safe=False, data=[])
        categorias.author_id = id
        categorias.save()
        # Código 201: Creada con éxito. La aplicación cliente debe registrar la pantalla principal pidiendo de nuevo las categorias con GET users/{id}/categories
        return JsonResponse(status=201, safe=False, data=[])
    else:
        # Error 405: Method Not Allowed (Método No Permitido, no es ni GET ni POST)
        return JsonResponse(status=405, safe=False, data=[])
