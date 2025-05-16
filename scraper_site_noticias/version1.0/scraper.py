import requests
from bs4 import BeautifulSoup

def get_links_from_url(url):
    try:
        response = requests.get(url, timeout=10)
        soup = BeautifulSoup(response.text, 'html.parser')

        links = []

        for a in soup.find_all('a'):
            text = a.get_text(strip=True)
            href = a.get('href')
            if href and text:
                links.append({
                    'titulo': text,
                    'link': href if href.startswith('http') else url + href
                })

        return links
    except Exception as e:
        return [{'titulo': 'Erro ao acessar o site:', 'link': str(e)}]
