import streamlit as st
import pandas as pd
import oracledb
from datetime import datetime

# Inicializa o cliente Oracle
oracledb.init_oracle_client(lib_dir=r"C:\\orcale\\instantclient_23_8")

# Função que consulta dados com base nos filtros
def buscar_dados(data_inicio, data_fim, nr_atendimento, cd_estabelecimento):
    conn = oracledb.connect(
        user="DANIEL_BERNARDES",
        password="1NQhm7Y4l7sN",
        dsn="hscexax8-scan.saocamilo.corp:1521/tasyspx8"
    )

    cursor = conn.cursor()

    # Montar a query dinamicamente com filtros opcionais
    query = """
        SELECT * FROM tasy.atendimento_paciente
        WHERE dt_entrada BETWEEN TO_DATE(:data_inicio, 'DD/MM/YYYY') AND TO_DATE(:data_fim, 'DD/MM/YYYY')
    """
    params = {
        "data_inicio": data_inicio.strftime("%d/%m/%Y"),
        "data_fim": data_fim.strftime("%d/%m/%Y")
    }

    if nr_atendimento:
        query += " AND NR_ATENDIMENTO = :nr_atendimento"
        params["nr_atendimento"] = int(nr_atendimento)

    if cd_estabelecimento:
        query += " AND CD_ESTABELECIMENTO = :cd_estabelecimento"
        params["cd_estabelecimento"] = int(cd_estabelecimento)

    cursor.execute(query, params)
    columns = [col[0] for col in cursor.description]
    rows = cursor.fetchall()

    df = pd.DataFrame(rows, columns=columns)

    # Formatar datas no formato DD/MM/YYYY HH:MM:SS
    for col in df.columns:
        if df[col].dtype == object:
            df[col] = df[col].apply(lambda x: x.strftime('%d/%m/%Y %H:%M:%S') if isinstance(x, datetime) else x)

    cursor.close()
    conn.close()
    return df

# ---------- INTERFACE ----------
st.title("Consulta de Atendimentos por Intervalo de Datas")

# Filtros de data
col1, col2 = st.columns(2)
with col1:
    data_inicio = st.date_input("Data inicial", datetime(2025, 5, 15), format="DD/MM/YYYY")
with col2:
    data_fim = st.date_input("Data final", datetime(2025, 5, 16), format="DD/MM/YYYY")

# Filtros adicionais
st.markdown("### Filtros adicionais:")
col3, col4 = st.columns(2)
with col3:
    nr_atendimento = st.text_input("Número do Atendimento (NR_ATENDIMENTO)", "")
with col4:
    cd_estabelecimento = st.text_input("Código do Estabelecimento (CD_ESTABELECIMENTO)", "")

# Validar intervalo
if data_inicio > data_fim:
    st.error("A data inicial não pode ser posterior à data final.")
else:
    if st.button("Buscar dados"):
        with st.spinner("Consultando o banco de dados..."):
            df = buscar_dados(data_inicio, data_fim, nr_atendimento, cd_estabelecimento)

            if not df.empty:
                st.success(f"{len(df)} registro(s) encontrado(s).")
                st.dataframe(df, use_container_width=True)

                csv = df.to_csv(index=False).encode('utf-8')
                st.download_button("Baixar CSV", csv, "atendimentos.csv", "text/csv")
            else:
                st.warning("Nenhum dado encontrado com os filtros aplicados.")
