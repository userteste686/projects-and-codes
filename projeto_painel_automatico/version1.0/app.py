import streamlit as st
import pandas as pd
import oracledb
from datetime import datetime

oracledb.init_oracle_client(lib_dir=r"C:\\orcale\\instantclient_23_8")

def buscar_dados(data_inicio):

    conn = oracledb.connect(
        user="DANIEL_BERNARDES",
        password="1NQhm7Y4l7sN",
        dsn="hscexax8-scan.saocamilo.corp:1521/tasyspx8"
    )

    cursor = conn.cursor()
    query = f"""
        SELECT * FROM tasy.atendimento_paciente
        WHERE dt_entrada > TO_DATE(:1, 'DD/MM/YYYY')
    """
    
    cursor.execute(query, [data_inicio.strftime("%d/%m/%Y")])
    
    columns = [col[0] for col in cursor.description]
    
    rows = cursor.fetchall()
    
    df = pd.DataFrame(rows, columns=columns)
    
    for col in df.columns:
        if df[col].dtype == object:
            df[col] = df[col].apply(lambda x: x.strftime('%Y-%m-%d %H:%M:%S') if isinstance(x, datetime) else x)
            
    cursor.close()
    conn.close()
    return df


st.title("Consulta de Atendimentos")
data_inicio = st.date_input("Selecione a data mínima de entrada", datetime(2025, 5, 15))

if st.button("Buscar dados"):
    with st.spinner("Consultando o banco de dados..."):
        df = buscar_dados(data_inicio)

        if not df.empty:
            st.success("Dados carregados com sucesso!")
            st.dataframe(df, use_container_width=True)
            # Download
            csv = df.to_csv(index=False).encode('utf-8')
            st.download_button("Baixar CSV", csv, "atendimentos.csv", "text/csv")
        else:
            st.warning("Nenhum dado encontrado para a data selecionada.")








 