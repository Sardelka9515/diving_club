document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const suggestionBox = document.getElementById("searchSuggestions");

    if (!searchInput || !suggestionBox) return;

    // 顯示建議選單
    searchInput.addEventListener("focus", () => {
        suggestionBox.style.display = "block";
    });

    // 點外面收起選單
    document.addEventListener("click", (e) => {
        if (!searchInput.contains(e.target) && !suggestionBox.contains(e.target)) {
            suggestionBox.style.display = "none";
        }
    });

    // 點關鍵字帶入 input 並提交
    document.querySelectorAll(".search-suggestion-item").forEach(item => {
        item.addEventListener("click", () => {
            searchInput.value = item.dataset.keyword;
            searchInput.form.submit();
        });
    });

    // 單筆刪除
    document.querySelectorAll(".remove-keyword-btn").forEach(btn => {
        btn.addEventListener("click", async (e) => {
            e.stopPropagation();
            const keyword = btn.dataset.keyword;
            const itemEl = btn.closest("li");

            try {
                const res = await fetch("/search/logs/delete", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").content,
                    },
                    body: JSON.stringify({ keyword })
                });

                if (res.ok) {
                    itemEl.remove();
                    window.recentKeywords = window.recentKeywords.filter(k => k !== keyword);

                    // 補一筆備用資料（如果有）
                    if (window.reserveKeywords && window.reserveKeywords.length > 0) {
                        const next = window.reserveKeywords.shift();
                        const newLi = document.createElement("li");
                        newLi.className = "list-group-item d-flex justify-content-between align-items-center";
                        newLi.innerHTML = `
                            <span class="search-suggestion-item" data-keyword="${next}" style="cursor:pointer">${next}</span>
                            <button type="button" class="remove-keyword-btn text-secondary border-0 bg-transparent" data-keyword="${next}">×</button>
                        `;

                        // 插在「清除紀錄」按鈕前
                        const clearBtnLi = document.getElementById("clearSearchItem");
                        suggestionBox.insertBefore(newLi, clearBtnLi);

                        newLi.querySelector(".search-suggestion-item").addEventListener("click", () => {
                            searchInput.value = next;
                            searchInput.form.submit();
                        });
                        newLi.querySelector(".remove-keyword-btn").addEventListener("click", async (e) => {
                            e.stopPropagation();
                            const subKeyword = next;
                            const subEl = newLi;
                            try {
                                const r = await fetch("/search/logs/delete", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").content,
                                    },
                                    body: JSON.stringify({ keyword: subKeyword })
                                });
                                if (r.ok) {
                                    subEl.remove();
                                    window.recentKeywords = window.recentKeywords.filter(k => k !== subKeyword);
                                    if (window.reserveKeywords && window.reserveKeywords.length > 0) {
                                        const again = window.reserveKeywords.shift();
                                        const againLi = document.createElement("li");
                                        againLi.className = "list-group-item d-flex justify-content-between align-items-center";
                                        againLi.innerHTML = `
                                            <span class="search-suggestion-item" data-keyword="${again}" style="cursor:pointer">${again}</span>
                                            <button type="button" class="remove-keyword-btn text-secondary border-0 bg-transparent" data-keyword="${again}">×</button>
                                        `;
                                        suggestionBox.insertBefore(againLi, clearBtnLi);
                                        againLi.querySelector(".search-suggestion-item").addEventListener("click", () => {
                                            searchInput.value = again;
                                            searchInput.form.submit();
                                        });
                                        againLi.querySelector(".remove-keyword-btn").addEventListener("click", (e) => {
                                            e.stopPropagation();
                                            deleteKeyword(again, againLi);
                                        });
                                    }
                                }
                            } catch (err) {
                                console.error("遞補刪除失敗", err);
                            }
                        });
                    }
                }
            } catch (err) {
                console.error("刪除失敗", err);
            }
        });
    });

    // 清除全部
    const clearBtn = document.getElementById("clearAllKeywords");
    if (clearBtn) {
        clearBtn.addEventListener("click", async () => {
            try {
                const res = await fetch("/search/logs/clear", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").content,
                    }
                });

                if (res.ok) {
                    suggestionBox.innerHTML = "";
                    suggestionBox.style.display = "none";
                }

            } catch (err) {
                console.error("清除全部失敗", err);
            }
        });
    }
});















