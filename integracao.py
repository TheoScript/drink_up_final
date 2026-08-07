import serial
import requests
import json
import time


# ==========================================
# CONFIGURAÇÃO SERIAL ESP32
# ==========================================

PORTA_SERIAL = "COM3"
BAUDRATE = 115200


# ==========================================
# CONFIGURAÇÃO API LARAVEL
# ==========================================

URL_API = "http://localhost:8000/api/iot/bebedouro/consumo"


# ==========================================
# IDENTIFICAÇÃO DO BEBEDOURO
# Deve existir na tabela bebedouros
# ==========================================

MAC_BEBEDOURO = "D8:BC:38:12:45:AA"



# ==========================================
# CONEXÃO SERIAL
# ==========================================

try:

    esp32 = serial.Serial(
        PORTA_SERIAL,
        BAUDRATE,
        timeout=1
    )

    time.sleep(2)

    print("--------------------------------")
    print("Bebedouro IoT iniciado")
    print("Porta:", PORTA_SERIAL)
    print("Aguardando consumo...")
    print("--------------------------------")


except Exception as erro:

    print("Erro ao abrir serial:")
    print(erro)
    exit()



# ==========================================
# ENVIA PARA API
# ==========================================

def enviar_api(rfid, volume):

    dados = {

        "mac_address": MAC_BEBEDOURO,
        "rfid": rfid,
        "volume": volume

    }


    try:

        resposta = requests.post(
            URL_API,
            json=dados,
            timeout=10
        )


        print("")
        print("==============================")
        print("Resposta API")
        print("==============================")
        print("Código:", resposta.status_code)
        print(resposta.text)
        print("==============================")
        print("")


    except Exception as erro:

        print("Erro ao comunicar com API:")
        print(erro)



# ==========================================
# MONITOR SERIAL
# ==========================================

while True:

    try:

        linha = esp32.readline().decode(
            "utf-8",
            errors="ignore"
        ).strip()


        if linha == "":
            continue


        print("ESP32:", linha)



        # ==================================
        # Verifica JSON recebido
        # ==================================

        if linha.startswith("{") and linha.endswith("}"):


            try:

                dados = json.loads(linha)


                if (
                    "rfid" in dados and
                    "volume_ml" in dados
                ):

                    rfid = dados["rfid"]

                    volume = dados["volume_ml"]


                    print("")
                    print("Consumo finalizado")
                    print("RFID:", rfid)
                    print("Volume:", volume,"ml")


                    enviar_api(
                        rfid,
                        volume
                    )


            except json.JSONDecodeError:

                print("JSON inválido recebido")


    except KeyboardInterrupt:

        print("")
        print("Programa encerrado")
        break


    except Exception as erro:

        print("Erro:")
        print(erro)