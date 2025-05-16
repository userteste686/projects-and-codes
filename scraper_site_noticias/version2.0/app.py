import streamlit as st
from scraper import get_headlines_from_url

st.title("📰 Webscraper de Manchetes")

url = st.text_input("Digite a URL do site de notícias:")

if url:
    with st.spinner("Buscando manchetes..."):
        manchetes = get_headlines_from_url(url)

    st.subheader("Manchetes encontradas:")
    for m in manchetes:
        st.markdown(f"- [{m['titulo']}]({m['link']})")
