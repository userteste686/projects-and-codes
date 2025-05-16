import requests
from bs4 import BeautifulSoup
import re

def get_headlines_from_url(url):
    try:
        response = requests.get(url, timeout=10)
        soup = BeautifulSoup(response.text, 'html.parser')

        headlines = []

        # Padrões comuns para manchetes
        for tag in soup.find_all(['h1', 'h2', 'h3', 'a']):
            text = tag.get_text(strip=True)
            href = tag.get('href')

            # Condições para filtrar texto útil
            if text and len(text) > 25:  # Evita títulos muito curtos
                if re.search(r'(noticia|news|headline|titulo|title)', str(tag.get('class')), re.IGNORECASE) or tag.name in ['h1', 'h2']:
                    link = href if href and href.startswith('http') else url + href if href else url
                    headlines.append({'titulo': text, 'link': link})

        # Remover duplicatas
        unique_headlines = {item['titulo']: item for item in headlines}
        return list(unique_headlines.values())

    except Exception as e:
        return [{'titulo': 'Erro ao acessar o site', 'link': str(e)}]
