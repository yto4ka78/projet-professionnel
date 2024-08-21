//FOR USER
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let totalPages = 1;
        function searchClubs(page = 1) {
            const nameInput = document.getElementById('user_form_name').value;
            const idInput = document.getElementById('user_form_ID').value;
    

            fetch('/search_user', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',  //Forma JSON
                    'X-Requested-With': 'XMLHttpRequest' //Request AJAX (information for server)
                },
                body: JSON.stringify({
                    name: nameInput,
                    id: idInput,
                    page: page
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success){
                const resultsContainer = document.getElementById('user_div_resulta_root');
                resultsContainer.innerHTML = '';
                data.users.forEach(function(user) {
                    const userHTML = `
                        <div class="profile_mes_clubs_resulta">
                            <div class="main_root_resulta_image">
                                <img src="/uploads/avatars_user/${user.Avatar || 'god.png'}" alt="No photo">
                            </div>
                            
                            <div class="main_root_resulta_text">
                                <p class="main_root_resulta_text_name"> ${user.name} </p>
                                <p class="main_root_resulta_text_info"> ID: ${user.id} </p>
                            </div>
                            <div style="flex: 1;">

                            </div>
                            <div class="main_root_resulta_btn">
                                <a class="activites_post_cancel" style="text-decoration: none;" href="/profile/${user.id}">Gerer</a>
                            </div>
                        </div>
                    `;
                    resultsContainer.insertAdjacentHTML('beforeend', userHTML);
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
            const paginationContainer = document.getElementById('user_pagination');
            paginationContainer.innerHTML = ''; 
        
            const maxVisiblePages = 5;
            let startPage, endPage;
        
           
            if (totalPages <= maxVisiblePages) {
                startPage = 1;
                endPage = totalPages;
            } else {
                
                startPage = Math.max(currentPage - 2, 1); 
                endPage = Math.min(currentPage + 2, totalPages); 
        
            
                if (endPage - startPage < maxVisiblePages - 1) {
                    if (startPage === 1) {
                        endPage = startPage + maxVisiblePages - 1;
                    } else if (endPage === totalPages) {
                        startPage = endPage - maxVisiblePages + 1;
                    }
                }
            }
        
            
            for (let i = startPage; i <= endPage; i++) {
                const pageButton = document.createElement('button');
                pageButton.textContent = i;
                pageButton.classList.add('page-btn');
                if (i === currentPage) {
                    pageButton.classList.add('active');
                }
                pageButton.addEventListener('click', function() {
                    searchClubs(i);
                });
                paginationContainer.appendChild(pageButton);
            }
        }
        
        
        searchClubs();

        document.getElementById('user_botton_change_page_right').addEventListener('click', function() {
            if (currentPage < totalPages) { 
                searchClubs(currentPage + 1); 
            }
        });

        document.getElementById('user_botton_change_page_left').addEventListener('click', function() {
            if (currentPage > 1) { 
                searchClubs(currentPage - 1); 
            }
        });

        document.getElementById('user_form_name').addEventListener('input', function() {
            searchClubs(1);
        });

        document.getElementById('user_form_id').addEventListener('input', function() {
            searchClubs(1);
        });
    });
//FOR CLUB
    document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let totalPages = 1;
        function searchClubs(page = 1) {
            const nameInput = document.getElementById('form_name').value;
            const regionInput = document.getElementById('form_code_postal').value;
            const idInput = document.getElementById('form_id').value;
    

            fetch('/search_clubs', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',  //Forma JSON
                    'X-Requested-With': 'XMLHttpRequest' //Request AJAX (information for server)
                },
                body: JSON.stringify({
                    name: nameInput,
                    region: regionInput,
                    id: idInput,
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
                        <div class="profile_mes_clubs_resulta">
                            <div class="main_root_resulta_image">
                                <img src="/uploads/avatars_club/${club.Avatar || 'god.png'}" alt="No photo">
                            </div>
                            
                            <div class="main_root_resulta_text">
                                <p class="main_root_resulta_text_name"> ${club.name} </p>
                                <p class="main_root_resulta_text_info"> ${club.region} </p>
                                <p class="main_root_resulta_text_info"> ID: ${club.id} </p>
                            </div>
                            <div style="flex: 1;">

                            </div>
                            <div class="main_root_resulta_btn">
                                <a class="activites_post_cancel" style="text-decoration: none;" href="/club_profile/${club.id}">Gerer</a>
                            </div>
                        </div>
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
        document.getElementById('form_code_postal').addEventListener('input', function() {
            searchClubs(1); 
        });
        document.getElementById('form_id').addEventListener('input', function() {
            searchClubs(1);
        });
    });