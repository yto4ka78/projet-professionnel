document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let totalPages = 1;
    const clubProfileUrl = "{{ path('club_profile', {'id': 'CLUB_ID'}) }}";
        function searchClubs(page = 1) {
            const nameInput = document.getElementById('form_name').value;
            const regionInput = document.getElementById('form_code_postal').value;
    
            fetch('/search_clubs_all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',  //Forma JSON
                    'X-Requested-With': 'XMLHttpRequest' //Request AJAX (information for server)
                },
                body: JSON.stringify({
                    name: nameInput,
                    region: regionInput,
                    page: page
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success){
                const resultsContainer = document.getElementById('div_resulta_root');

                resultsContainer.innerHTML = '';
                data.clubs.forEach(function(club) {
                    const clubHTML = `
                       <a href="/club_profile/${club.id}" class="recherche_page_resulta_link">
                        <div class="recherche_page_resulta">
                            <div class="resulta_photo">
                                <img src="/uploads/avatars_club/${club.Avatar || 'god.png'}">
                            </div>
                            <div class="resulta_text">
                                <p class="resulta_text_name">${club.name}</p>
                                <p class="resulta_text_description">${club.region}</p>
                                <p>${club.description}</p>
                            </div>
                        </div>
                        </a>
                    `;
                    resultsContainer.insertAdjacentHTML('beforeend', clubHTML);
                });
                totalPages = data.totalPages;
                currentPage = data.currentPage;
                updatePagination(data.totalPages, data.currentPage);       
                }else{
                    console.log(data.error);
                }

            })
            .catch(error => console.log('Error:', error));
        }

        function updatePagination(totalPages, currentPage) {
            const paginationContainer = document.getElementById('pagination');
            paginationContainer.innerHTML = ''; // Очищаем старую пагинацию
        
            const maxVisiblePages = 5; // Сколько страниц отображать
            let startPage, endPage;
        
            // Если страниц меньше или равно 5, показываем все
            if (totalPages <= maxVisiblePages) {
                startPage = 1;
                endPage = totalPages;
            } else {
                // Вычисляем диапазон отображаемых страниц
                startPage = Math.max(currentPage - 2, 1); // Начальная страница
                endPage = Math.min(currentPage + 2, totalPages); // Конечная страница
        
                // Корректируем диапазон, чтобы отображалось ровно 5 страниц
                if (endPage - startPage < maxVisiblePages - 1) {
                    if (startPage === 1) {
                        endPage = startPage + maxVisiblePages - 1;
                    } else if (endPage === totalPages) {
                        startPage = endPage - maxVisiblePages + 1;
                    }
                }
            }
        
            // Создаем кнопки пагинации
            for (let i = startPage; i <= endPage; i++) {
                const pageButton = document.createElement('button');
                pageButton.textContent = i;
                pageButton.classList.add('page-btn');
                if (i === currentPage) {
                    pageButton.classList.add('active');
                }
                pageButton.addEventListener('click', function() {
                    searchClubs(i); // Запрос новой страницы
                });
                paginationContainer.appendChild(pageButton);
            }
        }
        
        
        searchClubs();

        document.getElementById('botton_change_page_right').addEventListener('click', function() {
            if (currentPage < totalPages) { // Если не на последней странице
                searchClubs(currentPage + 1); // Переход на следующую страницу
            }
        });

        // Кнопка "влево"
        document.getElementById('botton_change_page_left').addEventListener('click', function() {
            if (currentPage > 1) { // Если не на первой странице
                searchClubs(currentPage - 1); // Переход на предыдущую страницу
            }
        });

        document.getElementById('form_name').addEventListener('input', function() {
            searchClubs(1);
        });
        document.getElementById('form_code_postal').addEventListener('change', function() {
            searchClubs(1); 
        });
    });
    