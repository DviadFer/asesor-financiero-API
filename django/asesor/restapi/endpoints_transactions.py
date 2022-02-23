from django.http import JsonResponse
from django.shortcuts import render
from .models import Ttransaction
from .models import Tuser
from .models import Tcategory
from django.http import HttpResponse
from django.views.decorators.csrf import csrf_exempt
import json
from datetime import datetime
from django.utils import timezone

@csrf_exempt
def transactions(request, id_solicitado):
	try:
		id_user = Tuser.objects.get(id=id_solicitado)
	except:
		return JsonResponse(status=404, safe=False, data={'message':'La peticion ha fallado porque el ID del usuario no existe'})
	try:
		token = request.headers['token']
	except:
		return JsonResponse(status=401, safe=False, data={'message':'La peticion ha fallado porque no se ha enviado el token de activacion de cabeceras'})
	if token != id_user.active_session_token:
		return JsonResponse(status=403, safe=False, data={'message':'La peticion ha fallado porque el token de sesion no es valido'})

	if (request.method == 'GET'):
		try:
			category = Tcategory.objects.filter(author = id_solicitado)

		except Tcategory.DoesNotExist or Ttransaction.DoesNotExist:
			return JsonResponse(status=404, safe=False, data={})
		requested_type = request.GET.get("type")
		lista_transaccion = []
		for fila in category:
			# Filtramos na consulta polo parametro 'type' da request, en caso de que sexa necesario
			if requested_type in ['spending', 'income']:
				transaction = Ttransaction.objects.filter(category = fila.id, type = requested_type)
			else:
				transaction = Ttransaction.objects.filter(category = fila.id)
			for fila_sql in transaction:
				diccionario = {}
				diccionario['datetime'] = fila_sql.datetime
				diccionario['amount'] = fila_sql.amount
				diccionario['category_name'] = fila_sql.category.name
				diccionario['type'] = fila_sql.type
				lista_transaccion.append(diccionario)
		lista_transaccion_ordenada = sorted(
			lista_transaccion,
			key=lambda x: x["datetime"],
			reverse=True
		)
		return JsonResponse(lista_transaccion_ordenada, json_dumps_params={'ensure_ascii':False}, safe=False)
	elif (request.method == 'POST'):
		transaction = Ttransaction()
		json_peticion = json.loads(request.body)
		try:
			transaction.datetime = timezone.now()
			transaction.amount = json_peticion['amount']
			transaction.category_id = json_peticion['category_id']
			transaction.type = json_peticion['type']
		except KeyError:
			return JsonResponse(status=400, data={"msg": "Missing parameter"})
		transaction.save()
		return JsonResponse(status=201, safe=False, data={})
	else:
		return JsonResponse(status=405, data={"msg": "Method not allowed"})
