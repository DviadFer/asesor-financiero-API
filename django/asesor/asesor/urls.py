"""asesor URL Configuration

The `urlpatterns` list routes URLs to views. For more information please see:
    https://docs.djangoproject.com/en/3.2/topics/http/urls/
Examples:
Function views
    1. Add an import:  from my_app import views
    2. Add a URL to urlpatterns:  path('', views.home, name='home')
Class-based views
    1. Add an import:  from other_app.views import Home
    2. Add a URL to urlpatterns:  path('', Home.as_view(), name='home')
Including another URLconf
    1. Import the include() function: from django.urls import include, path
    2. Add a URL to urlpatterns:  path('blog/', include('blog.urls'))
"""
from django.contrib import admin
from django.urls import path
from restapi import endpoints_categories, endpoints_transactions, endpoints_user

urlpatterns = [
   path('admin/', admin.site.urls),
   path('rest/version/1/users/<int:id>/categories', endpoints_categories.categorias_por_user_id),
   path('rest/version/1/users/<int:id_solicitado>/transactions', endpoints_transactions.transactions),
   path('rest/version/1/sessions', endpoints_user.login),
   path('rest/version/1/users', endpoints_user.register)
]
