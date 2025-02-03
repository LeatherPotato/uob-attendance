import sqlite3
import json
from os import walk


DB_PATH = "database.sqlite"
con = sqlite3.connect(DB_PATH)
cur = con.cursor()

f = []
for (dirpath, dirnames, filenames) in walk("db_gen_assets/modules"):
    f.extend(filenames)
    break

courses_name = "db_gen_assets/courses.json"
# courses = json.load(open(courses_name))

coursesArr = []
def get_courses_from_json(file_path):
    data = None
    with open(file_path, 'r') as file:
        data = json.load(file)
    courses = []
    for course in data:
        course_name = course.get('courseName')
        search_data_url = course.get('searchDataUrl')
        modules = course.get('modules')
        if course_name and search_data_url:
            coursesArr.append({'courseName': course_name, 'searchDataUrl': search_data_url, 'modules': modules})
            # print(f'Course Name: {course_name}\nSearch Data URL: {search_data_url}\n')

get_courses_from_json(courses_name)

def get_module(id):
    cur.execute("SELECT * FROM modules WHERE internal_module_id = ?", (id,))
    return cur.fetchone()

module = None
for file in f:
# file = f[0]
    with open("db_gen_assets/modules/"+file) as moduleFile:
        module = json.load(moduleFile)
        # cur.execute("INSERT INTO modules (module) VALUES (?)", (module,))
        # con.commit()
        # print(module['id'], module['name'])
    cur.execute("INSERT INTO modules (internal_module_id, name) VALUES (?, ?)", (int(module['id'][0]), module['name']))


for course in coursesArr:
    # print(course['courseName'])
    res = cur.execute('INSERT INTO courses (name) VALUES (?) RETURNING id', (course['courseName'],)).fetchone()
    courseId = res[0]
    for module in course['modules']:
        cur.execute('INSERT INTO course_module (course_id, module_id) VALUES (?, ?)', (courseId, get_module(module['id'][0])[0]))
con.commit()

print("DB GENERATED")