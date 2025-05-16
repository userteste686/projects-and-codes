import streamlit as st
from scraper import get_links_from_url

st.title("🔎 Webscraper de Notícias")

url = st.text_input("Digite a URL do site para coletar links de notícias:")

if url:
    with st.spinner("Coletando notícias..."):
        links = get_links_from_url(url)

    st.subheader("Resultados encontrados:")
    for item in links:
        st.markdown(f"- [{item['titulo']}]({item['link']})")
