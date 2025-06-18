import os

def read_and_backup_files():
    # Folder untuk menyimpan backup
    backup_folder = "backup"
    os.makedirs(backup_folder, exist_ok=True)  # Buat folder jika belum ada

    # Cari nama file backup berikutnya
    counter = 1
    while True:
        backup_file = os.path.join(backup_folder, f"debug{counter}.txt")
        if not os.path.exists(backup_file):
            break
        counter += 1

    # Ambil file di folder yang sama dengan script
    script_dir = os.path.dirname(os.path.abspath(__file__))
    files_in_dir = [f for f in os.listdir(script_dir) if os.path.isfile(os.path.join(script_dir, f))]

    # Buat isi file backup
    content = []
    for file_name in files_in_dir:
        try:
            with open(file_name, 'r', encoding='utf-8') as f:
                file_content = f.read()
            content.append(f"{file_name}\n(isi)\n{file_content}\n")
        except Exception as e:
            # Jika file tidak bisa dibaca (binary atau error), tulis pesan error
            content.append(f"{file_name}\n(isi)\n[ERROR: {str(e)}]\n")

    # Simpan isi ke file backup
    with open(backup_file, 'w', encoding='utf-8') as f:
        f.write("\n".join(content))
    
    print(f"Backup berhasil disimpan di {backup_file}")

if __name__ == "__main__":
    read_and_backup_files()
