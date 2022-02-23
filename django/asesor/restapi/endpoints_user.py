from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
import json
from .models import Tuser
import random, string
import bcrypt

def genTokenAleatorio():
	length_of_string = 20
	return (''.join(random.choice(string.ascii_letters + string.digits) for _ in range(length_of_string)))

@csrf_exempt
def login(request):
	json_body = json.loads(request.body)
	email_p = json_body['email']
	passwd_p = json_body['password']

	if email_p == "" or passwd_p == "":
		return JsonResponse(status=400, data={})

	try:
		fila_usuario = Tuser.objects.get(email=email_p)
		pass_database = fila_usuario.encrypted_password
#		Estos 2 Prints son  para hacer comprobacion de que llega en esas variables.
#		print(pass_database)
#		print(passwd_p)
#		Este if nos premite comparar las contraseñas sin estar encriptadas con hash
#		if fila_usuario.encrypted_password != passwd_p:
		if not bcrypt.checkpw(passwd_p.encode('utf-8'), pass_database.encode('utf-8')):

			return JsonResponse(status=401 , data ={})
	except Tuser.DoesNotExist:
		return JsonResponse(status=404, data={})

	#Continua sin problemas
	fila_usuario.active_session_token = genTokenAleatorio()
	fila_usuario.save()
	##Estas 3 Filas comentadas son codigo de prueba que muestre en la consola
	#del Servidor el Token y lo que manda la peticion JSON para el login
		#  print(fila_usuario.active_session_token)
		#  print(json_body)
		#  return JsonResponse(status=201, data ={})
	return JsonResponse(status=201, data={"user_id":fila_usuario.id, "name": fila_usuario.name,"token": fila_usuario.active_session_token})



@csrf_exempt
def register(request):
	json_body=json.loads(request.body)
	email_p = json_body['email']
	surname_p = json_body['surname']
	name_p = json_body['name']
	passwd_p = json_body['password']
	salt = bcrypt.gensalt()
	encrypted_pass = bcrypt.hashpw(passwd_p.encode('utf-8'),salt).decode('utf-8')

	if email_p == "" or surname_p == "" or name_p == "" or passwd_p == "":
		return JsonResponse(status=400, data={})
	try:
		fila_usuario = Tuser.objects.get(email=email_p)
	except Tuser.DoesNotExist:
		#Continua Sin problema
		nuevoUsuario = Tuser()
		nuevoUsuario.email = email_p
		nuevoUsuario.surname = surname_p
		nuevoUsuario.name = name_p
		nuevoUsuario.encrypted_password = encrypted_pass
		nuevoUsuario.save()
		return JsonResponse(status=201, data={})

	return JsonResponse(status=409, data={})

