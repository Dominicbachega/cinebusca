
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CineBusca</title>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #0b0b12;
    color: white;
    font-family: Arial, sans-serif;
}

header {
    padding: 28px 6%;
    background: #171720;
}

h1 {
    margin: 0;
    color: #e50950;
}

main {
    padding: 30px 6%;
}

.pesquisa {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

input, button {
    padding: 14px;
    border: 0;
    border-radius: 8px;
    font-size: 16px;
}

input {
    flex: 1;
    min-width: 200px;
    background: #22222e;
    color: white;
}

button {
    background: #e50950;
    color: white;
    cursor: pointer;
}

#status {
    color: #bbb;
    margin: 24px 0;
}

#resultados {
    display: grid;
    grid-template-columns:
        repeat(auto-fill, minmax(170px, 1fr));
    gap: 20px;
}

.card {
    background: #191923;
    border-radius: 10px;
    overflow: hidden;
}

.card img {
    width: 100%;
    aspect-ratio: 2 / 3;
    object-fit: cover;
    background: #292936;
    display: block;
}

.card h3 {
    margin: 12px;
}

.card p {
    margin: 12px;
    color: #ccc;
    font-size: 14px;
    line-height: 1.5;
}

.nota {
    color: #ffd166 !important;
}

</style>
</head>

<body>

<header>
    <h1>🎬 CineBusca</h1>
    <p>Pesquise suas séries favoritas</p>
</header>

<main>
    <form class="pesquisa" id="formBusca">
        <input
            id="termo"
            placeholder="Ex.: Breaking Bad"
            required
        >
        <button type="submit">Pesquisar</button>
    </form>

    <p id="status">Digite o nome de uma série.</p>
    <section id="resultados"></section>
</main>

<script>
const form = document.getElementById('formBusca');
const termo = document.getElementById('termo');
const statusBusca = document.getElementById('status');
const resultados = document.getElementById('resultados');

form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const q = termo.value.trim();
    if (!q) return;

    resultados.replaceChildren();
    statusBusca.textContent = 'Pesquisando...';

    try {
        const resposta = await fetch(
            'api.php?q=' + encodeURIComponent(q)
        );

        const dados = await resposta.json();

        if (!resposta.ok) {
            throw new Error(dados.error || 'Erro na busca.');
        }

        if (!dados.length) {
            statusBusca.textContent = 'Nenhuma série encontrada.';
            return;
        }

        statusBusca.textContent =
            dados.length + ' resultados encontrados.';

        dados.forEach(({ show }) => {
            const card = document.createElement('article');
            card.className = 'card';

            const img = document.createElement('img');
            img.alt = 'Pôster de ' + show.name;
            img.loading = 'lazy';
            img.src = show.image?.medium || '';

            const titulo = document.createElement('h3');
            titulo.textContent = show.name;

            const ano = document.createElement('p');
            ano.textContent = show.premiered
                ? show.premiered.slice(0, 4)
                : 'Ano desconhecido';

            const nota = document.createElement('p');
            nota.className = 'nota';
            nota.textContent =
                '★ ' + (show.rating?.average ?? 'Sem nota');

            const sinopse = document.createElement('p');
            sinopse.textContent = show.summary
                ? show.summary.replace(/<[^>]*>/g, '')
                : 'Sinopse indisponível.';

            card.append(img, titulo, ano, nota, sinopse);
            resultados.appendChild(card);
        });

    } catch (erro) {
        statusBusca.textContent =
            erro.message || 'Erro ao conectar à API.';
    }
});
</script>

</body>
</html>
